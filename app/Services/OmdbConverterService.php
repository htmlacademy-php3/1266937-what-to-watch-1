<?php

namespace App\Services;

class OmdbConverterService
{
    /**
     * Convert data from OMDb API into the DB format.
     *
     * @param array $omdbData
     */
    public function convert(array $omdbData): array
    {
        $data = collect($omdbData)->map(fn($value) => ($value === 'N/A' || empty($value)) ? null : $value);

        return [
            'name' => $data->get('Title'),
            'poster_image' => $data->get('Poster'),
            'description' => $data->get('Plot'),
            'run_time' => $this->parseRuntime($data->get('Runtime')),
            'released' => $this->parseYear($data->get('Year')),
            'genres' => $this->parseCsv($data->get('Genre')),
            'actors' => $this->parseCsv($data->get('Actors')),
            'directors' => $this->parseCsv($data->get('Director')),
        ];
    }

    /**
     * Parse runtime to integer from a raw string (e.g., '132 min').
     *
     * @param string|null $runtime
     *
     * @return int|null
     */
    private function parseRuntime(?string $runtime): ?int
    {
        if (empty($runtime)) {
            return null;
        }

        return preg_match('/\d+/', $runtime, $matches)
            ? (int) $matches[0]
            : null;
    }

    /**
     * Parse year to integer from a raw date string (e.g., '31 Mar 1999').
     *
     * @param string|null $date
     *
     * @return int|null
     */
    private function parseYear(?string $date): ?int
    {
        if (empty($date)) {
            return null;
        }

        return preg_match('/\b\d{4}\b/', $date, $matches)
            ? (int) $matches[0]
            : null;
    }

    /**
     * Parse a CSV string into an array.
     *
     * @param string|null $csvString
     *
     * @return string[]
     */
    private function parseCsv(?string $csvString): array
    {
        if (!$csvString) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $csvString)));
    }
}
