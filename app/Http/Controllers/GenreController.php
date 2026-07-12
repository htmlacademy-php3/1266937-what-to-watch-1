<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGenreRequest;
use App\Http\Responses\SuccessResponse;
use App\Models\Genre;

class GenreController extends Controller
{
    /**
     * Display a listing of the genres.
     *
     * @return SuccessResponse
     */
    public function index(): SuccessResponse
    {
        $genres = Genre::all();

        return $this->successResponse($genres);
    }

    /**
     * Update the specified genre in storage.
     *
     * @param UpdateGenreRequest $request
     * @param Genre $genre
     * @return SuccessResponse
     */
    public function update(UpdateGenreRequest $request, Genre $genre): SuccessResponse
    {
        $genre->update($request->validated());

        return $this->successResponse($genre);
    }
}
