<?php

namespace App\Queries;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Film;

class GetSimilarFilmsQuery
{
    public function __construct(
        protected GetFilmsQuery $baseFilmsQuery
    ) {
    }

    /**
     * Execute the query to get similar films with pagination.
     */
    public function execute(Film $film, ?int $userId = null, int $perPage = 4): LengthAwarePaginator
    {
        $genreIds = $film->genres->pluck('id')->toArray();

        if (empty($genreIds)) {
            return app(LengthAwarePaginator::class, [
                'items' => collect(),
                'total' => 0,
                'perPage' => $perPage
            ]);
        }

        $query = Film::where('films.id', '!=', $film->id)
            ->whereHas('genres', fn($q) => $q->whereIn('genres.id', $genreIds));

        return $this->baseFilmsQuery->execute(
            filters: ['user_id' => $userId],
            perPage: $perPage,
            baseQuery: $query
        );
    }
}
