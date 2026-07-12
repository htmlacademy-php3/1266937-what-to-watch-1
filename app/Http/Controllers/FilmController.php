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

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterFilmRequest $request, GetFilmsQuery $query): SuccessResponse
    {
        $validated = $request->validated();

        Gate::authorize('viewAny', [Film::class, $validated['status']]);

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
     * Store a newly created resource in storage.
     */
    public function store(StoreFilmRequest $request)
    {
        $data = $request->validated();

        $film = Film::create([
            'imdb_id' => $data['imdb_id'],
            'name' => 'Загрузка...',
            'status' => 'pending',
        ]);

        ProcessFilm::dispatch($data['imdb_id']);

        return $this->successResponse($film, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, GetFilmQuery $query): SuccessResponse
    {
        $userId = auth('sanctum')->id();

        $film = $query->execute($id, $userId);

        return $this->successResponse(FilmResource::make($film));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFilmRequest $request, Film $film): SuccessResponse
    {
        Gate::authorize('update', $film);

        $film->update($request->validated());

        return $this->successResponse(FilmResource::make($film));
    }

    /**
     * Display a listing of similar films
     */
    public function similar(Request $request, Film $film, GetSimilarFilmsQuery $query): SuccessResponse
    {
        $films = $query->execute(
            film: $film,
            userId: $request->user()?->id,
            perPage: 4
        );

        return $this->successResponse(FilmPreviewResource::collection($films));
    }

    /**
     * Display the promo film
     */
    public function showPromo(GetFilmQuery $query): SuccessResponse
    {
        $id = Cache::rememberForever(
            'promo_film_id',
            fn() => Film::where(
                'is_promo',
                true
            )->first()?->id
        );

        $promoFilm = $query->execute($id, auth('sanctum')->id());

        return $this->successResponse(FilmResource::make($promoFilm));
    }

    /**
     * Set the specified film as promo
     */
    public function setPromo(Film $film, SetPromoAction $action): SuccessResponse
    {
        $film = $action->execute($film)->refresh();

        return $this->successResponse(FilmResource::make($film));
    }
}
