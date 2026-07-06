<?php

namespace App\Queries;

use App\Models\Film;

class FetchFilmQuery
{
    /**
     * Fetch film by ID with all scopes and relations.
     */
    /**
     * Summary of execute
     * @param int $id
     * @param int|null $userId
     * @return Film|null
     */
    public function execute(int $id, ?int $userId = null): ?Film
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
