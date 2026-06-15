<?php

namespace App\Repositories\Interfaces;

interface FilmRepositoryInterface
{
    public function getFilmByImdbId(string $imdbId): ?array;
}
