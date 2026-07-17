<?php

namespace App\Http\Controllers;

use App\Queries\GetFilmsQuery;
use App\Http\Resources\FilmPreviewResource;
use App\Models\Film;

/**
 * @psalm-api
 */
class FavoriteController extends Controller
{
    /**
     * Display a listing of the favorite films.
     */
    public function index(GetFilmsQuery $query): \App\Http\Responses\SuccessResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $baseQuery = $user
            ->favoriteFilms()
            ->latest('favorite_film.id')
            ->getQuery();

        $filters = [
            'user_id' => auth()->id(),
        ];

        $films = $query->execute($filters, perPage: 8, baseQuery: $baseQuery);

        return $this->successResponse(FilmPreviewResource::collection($films));
    }

    /**
     * Store a newly created favorite film in storage.
     *
     * @return \App\Http\Responses\FailResponse|\App\Http\Responses\SuccessResponse
     */
    public function store(Film $film): \App\Http\Responses\SuccessResponse|\App\Http\Responses\FailResponse
    {
        $this->authorize('view', $film);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->favoriteFilms()->where('film_id', $film->id)->exists()) {
            return $this->failResponse(
                statusCode: 422,
                message: 'Этот фильм уже добавлен в Избранное'
            );
        }

        $user->favoriteFilms()->attach($film);

        return $this->successResponse([], 201);
    }

    /**
     * Remove the favorite film from storage.
     *
     * @return \App\Http\Responses\FailResponse|\App\Http\Responses\SuccessResponse
     */
    public function destroy(Film $film): \App\Http\Responses\SuccessResponse|\App\Http\Responses\FailResponse
    {
        $this->authorize('view', $film);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user->favoriteFilms()->where('film_id', $film->id)->exists()) {
            return $this->failResponse(
                statusCode: 422,
                message: 'Этот фильм отсутствует в списке Избранного'
            );
        }

        $user->favoriteFilms()->detach($film->id);

        return $this->successResponse([]);
    }
}
