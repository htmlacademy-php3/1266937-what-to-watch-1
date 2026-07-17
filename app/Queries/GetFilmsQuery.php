<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Film;
use App\Enums\FilmStatus;

final class GetFilmsQuery
{
    /**
     * Execute the query to get filtered films with pagination.
     *
     * @param array $filters Filters options.
     * @param int $perPage Items per page.
     * @param Builder|Relation|null $baseQuery Optional pre-configured query or relation.
     * @return LengthAwarePaginator<int, Film> Paginated collection of film models.
     */
    public function execute(
        array $filters,
        int $perPage = 8,
        Builder|Relation|null $baseQuery = null
    ): LengthAwarePaginator {

        /** @var Builder<Film> $query */
        $query = $baseQuery ?? Film::query();

        $query->withRating();
        $query->withIsFavorite($filters['user_id'] ?? null);

        $status = $filters['status'] ?? FilmStatus::Ready->value;

        $query->where('films.status', $status);

        if (!empty($filters['genre'])) {
            $query->whereRelation('genres', 'name', $filters['genre']);
        }

        return $query->paginate($perPage);
    }
}
