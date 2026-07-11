<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Film;
use App\Enums\FilmStatus;

class GetFilmsQuery
{
    /**
     * Execute the query to get filtered films with pagination.
     *
     * @param array{
     *  user_id?: int|null,
     *  status?: string,
     *  genre?: string,
     *  order_by?: string,
     *  order_to?: string
     * } $filters Filters and sorting options.
     * @param int $perPage Items per page.
     * @param Builder|Relation|null $baseQuery Optional pre-configured query or relation.
     * @return LengthAwarePaginator<Film> Paginated collection of film models.
     */
    public function execute(array $filters, int $perPage = 8, Builder|Relation|null $baseQuery = null): LengthAwarePaginator
    {
        $query = $baseQuery ?? Film::query();

        $query->withRating();
        $query->withIsFavorite($filters['user_id'] ?? null);

        $status = $filters['status'] ?? FilmStatus::Ready->value;

        $query->where('status', $status);

        if (!empty($filters['genre'])) {
            $query->whereRelation('genres', 'name', $filters['genre']);
        }

        return $query
            ->orderBy(
                $filters['order_by'] ?? 'released',
                $filters['order_to'] ?? 'desc'
            )
            ->paginate($perPage);
    }
}
