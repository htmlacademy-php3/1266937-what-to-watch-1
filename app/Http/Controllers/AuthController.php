<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        return new SuccessResponse();
    }

    public function login(Request $request)
    {
        return new SuccessResponse();
    }

    public function logout()
    {
        return new SuccessResponse();
    }
}
