<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Comment
 */

final class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'rating' => $this->rating,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user?->name,
            ],
            'user_id' => $this->user_id,
            'film_id' => $this->film_id,
            'comment_id' => $this->comment_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
