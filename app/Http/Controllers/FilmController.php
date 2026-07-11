<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Queries\GetFilmsQuery;
use App\Http\Requests\FilterFilmRequest;
use App\Http\Responses\PaginatedSuccessResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Requests\StoreFilmRequest;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Queries\GetFilmQuery;
use App\Jobs\ProcessFilm;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterFilmRequest $request, GetFilmsQuery $query): PaginatedSuccessResponse
    {
        $data = $request->validated();

        $this->authorize('viewAny', [Film::class, $data['status']]);

        $filters = [
            ...$data,
            'user_id' => $request->user()?->id,
        ];

        $paginator = $query->execute($filters, perPage: 8);

        return (new PaginatedSuccessResponse(
            FilmResource::collection($paginator->items())->resolve(),
            $paginator
        ));
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

        return new SuccessResponse($film, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, GetFilmQuery $query): SuccessResponse
    {
        $userId = auth('sanctum')->id();

        $film = $query->execute($id, $userId);

        return new SuccessResponse(new FilmResource($film));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Display a listing of similar films
     */
    public function similar()
    {
        return new SuccessResponse();
    }

    /**
     * Display the promo film
     */
    public function showPromo()
    {
        return new SuccessResponse();
    }

    /**
     * Set the specified film as promo
     */
    public function setPromo(string $id)
    {
        return new SuccessResponse();
    }
}
