<?php

namespace App\Repositories\Interfaces;

interface FilmRepositoryInterface
{
    /**
     * Fetch film data from external API by ID.
     *
     * @param string $id
     * @return array<string, mixed>|null
     */
    public function getFilmById(string $id): ?array;
}
