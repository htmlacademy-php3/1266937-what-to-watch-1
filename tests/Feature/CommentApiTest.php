<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Film;
use App\Models\Comment;
use App\Models\User;
use App\Models\Role;
use App\Enums\RoleName;
use App\Enums\FilmStatus;

uses(RefreshDatabase::class);

it('shows newest comments first', function () {
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);
    $user = User::factory()->create();

    $oldComment = Comment::factory()->create(['film_id' => $film->id, 'user_id' => $user->id, 'created_at' => now()->subDay()]);
    $newComment = Comment::factory()->create(['film_id' => $film->id, 'user_id' => $user->id, 'created_at' => now()]);

    $this->getJson("/api/comments/{$film->id}")
        ->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.id', $newComment->id)
        ->assertJsonPath('data.1.id', $oldComment->id)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'text', 'rating', 'user' => ['id', 'name'], 'user_id', 'film_id', 'comment_id', 'created_at', 'updated_at']
            ]
        ]);
});

it('returns 404 if film is not found when getting comments', function () {
    $this->getJson('/api/comments/999999')->assertStatus(404);
});

it('denies guest from creating comment', function () {
    $film = Film::factory()->create();
    $this->postJson("/api/comments/{$film->id}", ['text' => 'Отличный фильм', 'rating' => 5])->assertStatus(401);
});

it('allows an authenticated user to create comment', function () {
    $user = User::factory()->create();
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);
    $commentData = ['text' => 'Это совершенно потрясающий фильм, который заслуживает наивысшей оценки от каждого зрителя.', 'rating' => 10];

    $this->actingAs($user)
        ->postJson("/api/comments/{$film->id}", $commentData)
        ->assertStatus(200);

    $this->assertDatabaseHas('comments', ['film_id' => $film->id, 'user_id' => $user->id, 'text' => $commentData['text']]);
});

it('allows an authenticated user to reply to another comment', function () {
    $user = User::factory()->create();
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);
    $parentComment = Comment::factory()->create(['film_id' => $film->id]);
    $replyData = ['text' => 'Я полностью согласен с вашим мнением, этот комментарий идеально описывает всю суть кинокартины.', 'rating' => 9, 'comment_id' => $parentComment->id];

    $this->actingAs($user)
        ->postJson("/api/comments/{$film->id}", $replyData)
        ->assertStatus(200);

    $this->assertDatabaseHas('comments', ['comment_id' => $parentComment->id, 'text' => $replyData['text']]);
});

it('allows an author to update comment', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);
    $updateData = ['text' => 'Я решил полностью переписать свой отзыв, так как при повторном просмотре заметил много новых деталей.', 'rating' => 8];

    $this->actingAs($user)
        ->patchJson("/api/comments/{$comment->id}", $updateData)
        ->assertStatus(200);

    $this->assertDatabaseHas('comments', ['id' => $comment->id, 'text' => $updateData['text']]);
});

it('allows a moderator to update any comment', function () {
    User::factory()->create();

    $moderator = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => RoleName::Moderator->value])->id
    ]);

    $comment = Comment::factory()->create();
    $updateData = ['text' => 'Данный текст был отредактирован дежурным модератором из-за нарушения действующих правил публикации.', 'rating' => 5];

    $this->actingAs($moderator)
        ->patchJson("/api/comments/{$comment->id}", $updateData)
        ->assertStatus(200);

    $this->assertDatabaseHas('comments', ['id' => $comment->id, 'text' => $updateData['text']]);
});

it('allows an author to delete comment without replies', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->deleteJson("/api/comments/{$comment->id}")
        ->assertStatus(200);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

it('denies an author from deleting comment if it has replies', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);
    Comment::factory()->create(['comment_id' => $comment->id]);

    $this->actingAs($user)
        ->deleteJson("/api/comments/{$comment->id}")
        ->assertStatus(403);

    $this->assertDatabaseHas('comments', ['id' => $comment->id]);
});

it('allows a moderator to delete any comment and all replies', function () {
    User::factory()->create();

    $moderator = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => RoleName::Moderator->value])->id
    ]);

    $comment = Comment::factory()->create();
    $reply = Comment::factory()->create(['comment_id' => $comment->id]);

    $this->actingAs($moderator)
        ->deleteJson("/api/comments/{$comment->id}")
        ->assertStatus(200);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    $this->assertDatabaseMissing('comments', ['id' => $reply->id]);
});
