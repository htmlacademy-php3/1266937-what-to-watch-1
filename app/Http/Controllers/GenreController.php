<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new SuccessResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return new SuccessResponse();
    }
}
