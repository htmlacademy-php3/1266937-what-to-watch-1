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


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Film $film, CreateCommentAction $action)
    {
        Gate::authorize('create', Comment::class);

        $userId = auth()->id();
        $validated = $request->validated();

        $comment = $action->execute($film, $validated, $userId);

        $data = CommentResource::make($comment);

        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $comment->update($request->validated());

        $data = CommentResource::make($comment);

        return $this->successResponse($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->replies()->delete();

        $comment->delete();

        return $this->successResponse([]);
    }
}
