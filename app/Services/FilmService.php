<?php

namespace App\Services;

use App\Repositories\Interfaces\FilmRepositoryInterface;

class FilmService
{
    public function __construct(
        private FilmRepositoryInterface $filmRepository
    ) {
    }

    public function getFilm(string $imdbId): ?array
    {
        return $this->filmRepository->getFilmByImdbId($imdbId);
    }
}
