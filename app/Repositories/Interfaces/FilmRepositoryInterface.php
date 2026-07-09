<?php

namespace App\Repositories\Interfaces;

interface FilmRepositoryInterface
{
    public function getFilmById(string $id): ?array;
}
