<?php

namespace App\Actions;

use App\Models\Film;
use App\Models\Comment;

class CreateCommentAction
{
    public function execute(Film $film, array $data, int $userId): Comment
    {
        return Comment::with('user')->create([
            'film_id' => $film->id,
            'user_id' => $userId,
            'text' => $data['text'],
            'rating' => $data['rating'],
            'comment_id' => $data['comment_id'] ?? null,
        ]);
    }
}
