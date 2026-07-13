<?php

namespace App\Repositories;

use App\Repositories\Interfaces\FilmRepositoryInterface;
use Illuminate\Support\Facades\Http;

class OmdbFilmRepository implements FilmRepositoryInterface
{
    private const string BASE_URL = 'http://omdbapi.com';

    public function __construct(
        private string $apiKey,
    ) {
    }

    public function getFilmById(string $id): ?array
    {
        $response = Http::get(self::BASE_URL, [
            'apikey' => $this->apiKey,
            'i' => $id,
        ]);

        if ($response->failed() || ($response['Response'] ?? '') === 'False') {
            return null;
        }

        return $response->json();
    }
}
