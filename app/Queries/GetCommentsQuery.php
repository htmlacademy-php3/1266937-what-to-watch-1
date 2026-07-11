<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Film;
use App\Models\Comment;

class GetCommentsQuery
{
    /**
     * Execute the query to get film reviews.
     *
     * @param Film $film
     * @return Collection<int, Comment>
     */
    public function execute(Film $film): Collection
    {
        return $film->comments()
            ->whereNull('comment_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
