<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\RateLimited;
use Exception;
use App\Services\FilmService;
use App\Services\OmdbConverterService;
use App\Models\Film;
use App\Models\Genre;
use \App\Models\Actor;
use App\Models\Director;
use App\Enums\FilmStatus;

class ProcessFilm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $imdbId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(FilmService $filmService, OmdbConverterService $converter): void
    {
        $externalData = $filmService->getFilm($this->imdbId);

        $filmData = $converter->convert($externalData);

        $film = Film::where('imdb_id', $this->imdbId)->firstOrFail();

        $film->name = $filmData['name'];
        $film->poster_image = $filmData['poster_image'];
        $film->description = $filmData['description'];
        $film->run_time = $filmData['run_time'];
        $film->released = $filmData['released'];

        $film->status = FilmStatus::OnModeration;

        $film->save();

        $this->syncRelations($film, $filmData);
    }

    private function syncRelations(Film $film, array $filmData): void
    {
        $genreIds = [];
        foreach ($filmData['genres'] as $name) {
            $genreIds[] = Genre::firstOrCreate(['name' => $name])->id;
        }

        $film->genres()->sync($genreIds);

        $actorIds = [];
        foreach ($filmData['actors'] as $name) {
            $actorIds[] = Actor::firstOrCreate(['name' => $name])->id;
        }

        $film->actors()->sync($actorIds);

        $directorIds = [];
        foreach ($filmData['directors'] as $name) {
            $directorIds[] = Director::firstOrCreate(['name' => $name])->id;
        }

        $film->directors()->sync($directorIds);
    }

    public function middleware(): array
    {
        return [new RateLimited('film-api-limit')];
    }
}
