<?php

namespace App\Http\Controllers;

use App\Queries\GetFilmsQuery;
use App\Http\Resources\FilmPreviewResource;
use App\Models\Film;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the favorite films.
     */
    public function index(GetFilmsQuery $query)
    {
        $baseQuery = auth()->user()
            ->favoriteFilms()
            ->latest('favorite_film.id');

        $filters = [
            'user_id' => auth()->id(),
        ];

        $films = $query->execute($filters, perPage: 8, baseQuery: $baseQuery);

        return $this->successResponse(FilmPreviewResource::collection($films));
    }

    /**
     * Store a newly created favorite film in storage.
     */
    public function store(Film $film)
    {
        $this->authorize('view', $film);

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
     */
    public function destroy(Film $film)
    {
        $this->authorize('view', $film);

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
