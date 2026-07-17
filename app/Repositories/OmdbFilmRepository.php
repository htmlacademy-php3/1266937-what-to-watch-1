<?php

namespace App\Repositories;

use App\Repositories\Interfaces\FilmRepositoryInterface;
use Illuminate\Support\Facades\Http;

final class OmdbFilmRepository implements FilmRepositoryInterface
{
    private const string BASE_URL = 'http://omdbapi.com';

    public function __construct(
        private string $apiKey,
    ) {
    }

    #[\Override]
    public function getFilmById(string $id): ?array
    {
        $response = Http::get(self::BASE_URL, [
            'apikey' => $this->apiKey,
            'i' => $id,
        ]);

        /** @var \Illuminate\Http\Client\Response $response */
        if ($response->failed() || ($response['Response'] ?? '') === 'False') {
            return null;
        }

        /** @var array|null $data */
        $data = $response->json();

        return $data;
    }
}
