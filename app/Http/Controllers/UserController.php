<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\SuccessResponse;
use App\Http\Resources\UserResource;
use App\Actions\UpdateUserAction;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request): SuccessResponse
    {
        $user = $request->user()->load('role');

        return $this->successResponse(UserResource::make($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, UpdateUserAction $action): SuccessResponse
    {
        $user = $action->handle(
            $request->user(),
            $request->validated(),
            $request->file('file')
        );

        return $this->successResponse(UserResource::make($user));
    }
}
