<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns user name for user comment', function () {
    $user = User::factory(['name' => 'John Smith'])->create();

    $comment = Comment::factory(['user_id' => $user->id])->create();

    expect($comment->author_name)->toBe('John Smith');
});

it('returns default text for guest comment', function () {
    $comment = Comment::factory(['user_id' => null])->create();

    expect($comment->author_name)->toBe('Гость');
});
