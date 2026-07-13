<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\RateLimited;
use App\Repositories\Interfaces\FilmRepositoryInterface;
use App\Services\OmdbConverterService;
use App\Models\Film;
use App\Models\Genre;
use App\Models\Actor;
use App\Models\Director;
use App\Enums\FilmStatus;

/**
 * @psalm-api
 */
class ProcessFilm implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $imdbId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(FilmRepositoryInterface $filmRepository, OmdbConverterService $converter): void
    {
        $externalData = $filmRepository->getFilmById($this->imdbId);

        if ($externalData === null) {
            return;
        }

        $filmData = $converter->convert($externalData);

        $film = Film::query()->where('imdb_id', $this->imdbId)->firstOrFail();

        $film->name = $filmData['name'];
        $film->poster_image = $filmData['poster_image'];
        $film->description = $filmData['description'];
        $film->run_time = $filmData['run_time'];
        $film->released = $filmData['released'];

        $film->status = FilmStatus::OnModeration->value;

        $film->save();

        $this->syncRelations($film, $filmData);
    }

    /**
     * Sync film relations.
     *
     * @param array{
     *     genres: array<int, string>,
     *     actors: array<int, string>,
     *     directors: array<int, string>,
     *     ...
     * } $filmData
     */
    private function syncRelations(Film $film, array $filmData): void
    {
        $genreIds = [];
        foreach ($filmData['genres'] as $name) {
            $genreIds[] = Genre::query()->firstOrCreate(['name' => $name])->id;
        }

        $film->genres()->sync($genreIds);

        $actorIds = [];
        foreach ($filmData['actors'] as $name) {
            $actorIds[] = Actor::query()->firstOrCreate(['name' => $name])->id;
        }

        $film->actors()->sync($actorIds);

        $directorIds = [];
        foreach ($filmData['directors'] as $name) {
            $directorIds[] = Director::query()->firstOrCreate(['name' => $name])->id;
        }

        $film->directors()->sync($directorIds);
    }

    /**
     * Apply rate limiting middleware to the job.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [new RateLimited('film-api-limit')];
    }
}
