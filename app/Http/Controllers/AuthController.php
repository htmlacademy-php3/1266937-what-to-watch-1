<?php

namespace App\Http\Controllers;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Responses\SuccessResponse;
use App\Actions\RegisterAction;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;

/**
 * @psalm-api
 */
class AuthController extends Controller
{
    /**
     * Register a new user.
     */
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

    /**
     * Log in a user.
     */
    public function login(LoginRequest $request): SuccessResponse
    {
        if (!Auth::attempt($request->validated())) {
            throw ValidationException::withMessages(['email' => [trans('auth.failed')]]);
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->successResponse([
            'user' => UserResource::make($user),
            'token' => $token,
        ]);
    }

    /**
     * Log out a user.
     */
    public function logout(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        $token->delete();

        return response()->noContent();
    }
}
