<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

/**
 * @psalm-api
 */
class CommentPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $_ability): ?bool
    {
        if ($user->isModerator()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id && !$comment->replies()->exists();
    }
}
