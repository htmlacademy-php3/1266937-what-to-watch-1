<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Responses\SuccessResponse;
use App\Models\Comment;
use App\Models\Film;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Queries\GetCommentsQuery;
use App\Http\Requests\StoreCommentRequest;
use App\Actions\CreateCommentAction;

/**
 * @psalm-api
 */
class CommentController extends Controller
{
    /**
     * Display a listing of the comments.
     */
    public function index(Film $film, GetCommentsQuery $query): SuccessResponse
    {
        $comments = $query->execute($film);

        $data = CommentResource::collection(
            $comments
        );

        return $this->successResponse($data);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(StoreCommentRequest $request, Film $film, CreateCommentAction $action): SuccessResponse
    {
        $userId = (int) auth()->id();
        $validated = $request->validated();

        $comment = $action->execute($film, $validated, $userId);

        $data = CommentResource::make($comment);

        return $this->successResponse($data);
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment): SuccessResponse
    {
        Gate::authorize('update', $comment);

        $comment->update($request->validated());

        $data = CommentResource::make($comment);

        return $this->successResponse($data);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment): SuccessResponse
    {
        Gate::authorize('delete', $comment);

        $comment->replies()->newQuery()->delete();

        $comment->delete();

        return $this->successResponse([]);
    }
}
