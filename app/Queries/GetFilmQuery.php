<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Film;

final class GetFilmQuery
{
    /**
     * Get film by ID with scopes and relations.
     *
     * @throws ModelNotFoundException
     */
    public function execute(int $id, ?int $userId = null): Film
    {
        return Film::query()
            ->with(['genres', 'actors', 'directors'])
            ->withRating()
            ->withScoresCount()
            ->withIsFavorite($userId)
            ->where('id', $id)
            ->firstOrFail();
    }
}
