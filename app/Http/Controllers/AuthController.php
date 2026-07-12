<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Actions\Auth\RegisterAction;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterAction $action): SuccessResponse
    {
        $data = $request->safe()->except('file');
        $user = $action->execute($data);
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->successResponse([
            'user' => UserResource::make($user),
            'token' => [
                'user' => UserResource::make($user),
                'token' => $token,
            ],
        ], 201);
    }

    public function login(LoginRequest $request): SuccessResponse
    {
        if (!Auth::attempt($request->validated())) {
            throw ValidationException::withMessages(['email' => [trans('auth.failed')]]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->successResponse([
            'user' => UserResource::make($user),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
