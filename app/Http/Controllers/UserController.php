<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show()
    {
        return new SuccessResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        return new SuccessResponse();
    }
}
