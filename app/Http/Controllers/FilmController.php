<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new SuccessResponse();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return new SuccessResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new SuccessResponse();
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
