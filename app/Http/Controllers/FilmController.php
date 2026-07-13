<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use App\Queries\GetFilmsQuery;
use App\Http\Requests\FilterFilmRequest;
use App\Http\Responses\SuccessResponse;
use App\Http\Requests\StoreFilmRequest;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Http\Resources\FilmPreviewResource;
use App\Queries\GetFilmQuery;
use App\Jobs\ProcessFilm;
use App\Http\Requests\UpdateFilmRequest;
use App\Queries\GetSimilarFilmsQuery;
use App\Actions\SetPromoAction;

/**
 * @psalm-api
 */
class FilmController extends Controller
{
    /**
     * Display a listing of the films.
     */
    public function index(FilterFilmRequest $request, GetFilmsQuery $query): SuccessResponse
    {
        $validated = $request->validated();

        Gate::authorize('viewAny', [Film::class, $validated['status'] ?? null]);

        $filters = [
            ...$validated,
            'user_id' => $request->user()?->id,
        ];

        $baseQuery = Film::query()->orderBy(
            $filters['order_by'] ?? 'released',
            $filters['order_to'] ?? 'desc'
        );

        $data = $query->execute($filters, perPage: 8, baseQuery: $baseQuery);

        return $this->successResponse(FilmPreviewResource::collection($data));
    }

    /**
     * Store a newly created film in storage.
     */
    public function store(StoreFilmRequest $request): SuccessResponse
    {
        $data = $request->validated();

        $film = Film::query()->create([
            'imdb_id' => $data['imdb_id'],
            'name' => 'Загрузка...',
            'status' => 'pending',
        ]);

        ProcessFilm::dispatch((string) $data['imdb_id']);

        return $this->successResponse($film, 201);
    }

    /**
     * Display the specified film.
     */
    public function show(string $id, GetFilmQuery $query): SuccessResponse
    {
        $userId = (int) auth()->id();

        $film = $query->execute((int) $id, $userId);

        return $this->successResponse(FilmResource::make($film));
    }

    /**
     * Update the specified film in storage.
     */
    public function update(UpdateFilmRequest $request, Film $film): SuccessResponse
    {
        $film->update($request->validated());

        return $this->successResponse(FilmResource::make($film));
    }

    /**
     * Display a listing of similar films.
     */
    public function similar(Film $film, GetSimilarFilmsQuery $query): SuccessResponse
    {
        $userId = (int) auth()->id() ?: null;

        $films = $query->execute($film, $userId);

        return $this->successResponse(FilmPreviewResource::collection($films));
    }

    /**
     * Display the promo film.
     */
    public function showPromo(GetFilmQuery $query): SuccessResponse
    {
        $id = Cache::rememberForever(
            'promo_film_id',
            static fn() => Film::query()->where('is_promo', true)->first()?->id
        );

        $userId = (int) auth()->id() ?: null;

        $promoFilm = $query->execute((int) $id, $userId);

        return $this->successResponse(FilmResource::make($promoFilm));
    }

    /**
     * Set the specified film as a promo.
     */
    public function setPromo(Film $film, SetPromoAction $action): SuccessResponse
    {
        $film = $action->execute($film)->refresh();

        return $this->successResponse(FilmResource::make($film));
    }
}
