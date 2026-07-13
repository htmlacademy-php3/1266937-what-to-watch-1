<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\SuccessResponse;
use App\Http\Resources\UserResource;
use App\Actions\UpdateUserAction;
use App\Http\Requests\UpdateUserRequest;

/**
 * @psalm-api
 */
class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request): SuccessResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->load('role');

        return $this->successResponse(UserResource::make($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, UpdateUserAction $action): SuccessResponse
    {
        $data = $request->validated();

        /** @var \App\Models\User $user */
        $user = $request->user();

        /** @var \Illuminate\Http\UploadedFile|null $file */
        $file = $request->file('file') instanceof \Illuminate\Http\UploadedFile
            ? $request->file('file')
            : null;

        $updatedUser = $action->handle($user, $data, $file);

        return $this->successResponse(UserResource::make($updatedUser));
    }
}
