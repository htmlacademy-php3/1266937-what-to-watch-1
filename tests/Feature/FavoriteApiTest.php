<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Film;
use App\Models\User;
use App\Enums\FilmStatus;

uses(RefreshDatabase::class);

it('shows newest favorite films first', function () {
    $user = User::factory()->create();

    $oldFavorite = Film::factory()->create(['status' => FilmStatus::Ready->value]);
    $newFavorite = Film::factory()->create(['status' => FilmStatus::Ready->value]);

    $user->favoriteFilms()->attach($oldFavorite->id);
    $user->favoriteFilms()->attach($newFavorite->id);

    $response = $this->actingAs($user)
        ->getJson('/api/favorite');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.id', $newFavorite->id)
        ->assertJsonPath('data.1.id', $oldFavorite->id)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'preview_image',
                    'preview_video_link',
                ],
            ],
            'current_page',
            'first_page_url',
            'next_page_url',
            'prev_page_url',
            'per_page',
            'total',
        ]);
});

it('denies a guest from viewing favorite films', function () {
    $this->getJson('/api/favorite')->assertStatus(401);
});

it('allows an authenticated user to add a film to favorites', function () {
    $user = User::factory()->create();
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);

    $this->actingAs($user)
        ->postJson("/api/films/{$film->id}/favorite")
        ->assertStatus(201);

    $this->assertDatabaseHas('favorite_film', [
        'user_id' => $user->id,
        'film_id' => $film->id,
    ]);
});

it('returns 422 if a film is already in favorites when adding', function () {
    $user = User::factory()->create();
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);

    $user->favoriteFilms()->attach($film->id);

    $this->actingAs($user)
        ->postJson("/api/films/{$film->id}/favorite")
        ->assertStatus(422);
});

it('returns 404 if a film is not found when adding', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/films/999999/favorite')
        ->assertStatus(404);
});

it('allows an authenticated user to remove a film from favorites', function () {
    $user = User::factory()->create();
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);

    $user->favoriteFilms()->attach($film->id);

    $this->actingAs($user)
        ->deleteJson("/api/films/{$film->id}/favorite")
        ->assertStatus(200);

    $this->assertDatabaseMissing('favorite_film', [
        'user_id' => $user->id,
        'film_id' => $film->id,
    ]);
});

it('returns 422 if a film is not in favorites when removing', function () {
    $user = User::factory()->create();
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);

    $this->actingAs($user)
        ->deleteJson("/api/films/{$film->id}/favorite")
        ->assertStatus(422);
});

it('returns 404 if a film is not found when removing', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->deleteJson('/api/films/999999/favorite')
        ->assertStatus(404);
});
