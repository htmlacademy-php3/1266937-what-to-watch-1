<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Film;
use App\Enums\FilmStatus;

class GetSimilarFilmsQuery
{
    public function __construct(
        protected GetFilmsQuery $baseFilmsQuery
    ) {
    }

    /**
     * Execute the query to get similar films.
     */
    public function execute(Film $film, ?int $userId = null, int $limit = 4): Collection
    {
        $genreNames = $film->genres()->pluck('name');

        return Film::query()
            ->withRating()
            ->withIsFavorite($userId)
            ->where('films.status', FilmStatus::Ready->value)
            ->whereHas('genres', function ($query) use ($genreNames) {
                $query->whereIn('name', $genreNames);
            })
            ->whereKeyNot($film->id)
            ->limit($limit)
            ->get();
    }
}
