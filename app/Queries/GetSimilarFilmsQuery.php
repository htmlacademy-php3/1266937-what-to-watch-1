<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Film;
use App\Enums\FilmStatus;

final class GetSimilarFilmsQuery
{
    /**
     * Get similar films by genres.
     *
     * @return Collection<int, Film>
     */
    public function execute(Film $film, ?int $userId = null, int $limit = 4): Collection
    {
        $genreNames = $film->genres()->pluck('name');

        return Film::query()
            ->withRating()
            ->withIsFavorite($userId)
            ->where('films.status', FilmStatus::Ready->value)
            ->whereHas('genres', function (\Illuminate\Database\Eloquent\Builder $query) use ($genreNames) {
                $query->whereIn('name', $genreNames);
            })
            ->whereKeyNot($film->id)
            ->limit($limit)
            ->get();
    }
}
