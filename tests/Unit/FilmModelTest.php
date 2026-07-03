<?php

use App\Models\Film;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('calculates the average rating for a film', function () {
    $film = Film::factory()->create();

    Comment::factory(['film_id' => $film->id, 'rating' => 8])->create();
    Comment::factory(['film_id' => $film->id, 'rating' => 7])->create();
    Comment::factory(['film_id' => $film->id, 'rating' => 7])->create();

    $film = Film::withRating()->find($film->id);

    expect($film->rating)->toBe('7.3');
});
