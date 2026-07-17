<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Enums\FilmStatus;
use App\Jobs\ProcessFilm;
use App\Models\Film;
use App\Models\User;
use App\Models\Genre;
use App\Models\Role;
use App\Enums\RoleName;

uses(RefreshDatabase::class);

it('shows available films with pagination', function () {
    Film::factory()->count(10)->create(['status' => FilmStatus::Ready->value]);

    $response = $this->getJson('/api/films');

    $response->assertStatus(200)
        ->assertJsonCount(8, 'data')
        ->assertJsonPath('total', 10)
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

it('allows a moderator to filter films by status', function () {
    $moderator = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => RoleName::Moderator->value])->id
    ]);

    Film::factory()->count(5)->create(['status' => FilmStatus::Ready->value]);
    Film::factory()->count(3)->create(['status' => FilmStatus::Pending->value]);

    $response = $this->actingAs($moderator)
        ->getJson('/api/films?status=pending');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('total', 3);
});

it('filters films by genre', function () {
    $comedyFilm = Film::factory()->create(['status' => FilmStatus::Ready->value]);
    $actionFilm = Film::factory()->create(['status' => FilmStatus::Ready->value]);

    $comedy = Genre::factory()->create(['name' => 'Comedy']);
    $action = Genre::factory()->create(['name' => 'Action']);

    $comedyFilm->genres()->attach($comedy);
    $actionFilm->genres()->attach($action);

    $response = $this->getJson('/api/films?genre=Comedy');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $comedyFilm->id);
});

it('allows anyone to view a ready film', function () {
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);

    $response = $this->getJson("/api/films/{$film->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'released'
            ],
        ]);
});

it('includes favorite status for authenticated users', function () {
    $user = User::factory()->create();
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);

    if (method_exists($user, 'favoriteFilms')) {
        $user->favoriteFilms()->attach($film->id);
    }

    $response = $this->actingAs($user)
        ->getJson("/api/films/{$film->id}");

    $response->assertOk()
        ->assertJsonPath('data.is_favorite', true);
});

it('returns 404 if film is not found', function () {
    $response = $this->getJson('/api/films/999999');

    $response->assertStatus(404);
});

it('denies a guest from storing a film', function () {
    $response = $this->postJson('/api/films', ['imdb_id' => 'tt1234567']);

    $response->assertStatus(401);
});

it('returns 403 when a regular user stores a film', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/films', ['imdb_id' => 'tt1234567']);

    $response->assertStatus(403);
});

it('allows a moderator to store a film', function () {
    Queue::fake();
    $imdbId = 'tt1234567';

    $moderator = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => RoleName::Moderator->value])->id
    ]);

    $response = $this->actingAs($moderator)
        ->postJson('/api/films', ['imdb_id' => $imdbId]);

    $response->assertCreated();

    $this->assertDatabaseHas('films', [
        'imdb_id' => $imdbId,
        'name' => 'Загрузка...',
        'status' => FilmStatus::Pending->value,
    ]);

    Queue::assertPushed(ProcessFilm::class);
});

it('denies a guest from updating a film', function () {
    $film = Film::factory()->create();

    $response = $this->patchJson("/api/films/{$film->id}", [
        'imdb_id' => 'tt1234567',
        'name' => 'Titanic',
        'status' => FilmStatus::Ready->value,
    ]);

    $response->assertStatus(401);
});

it('denies a regular user from updating a film', function () {
    $user = User::factory()->create();
    $film = Film::factory()->create();

    $response = $this->actingAs($user)
        ->patchJson("/api/films/{$film->id}", [
            'imdb_id' => 'tt2278388',
            'name' => 'The Grand Budapest Hotel',
            'status' => FilmStatus::Ready->value,
        ]);

    $response->assertStatus(403);
});

it('allows a moderator to update film data', function () {
    User::factory()->create();

    $moderator = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => RoleName::Moderator->value])->id
    ]);

    $film = Film::factory()->create();

    $response = $this->actingAs($moderator)
        ->patchJson("/api/films/{$film->id}", [
            'imdb_id' => $film->imdb_id,
            'name' => 'The Grand Budapest Hotel',
            'status' => FilmStatus::Ready->value,
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('films', [
        'id' => $film->id,
        'name' => 'The Grand Budapest Hotel',
    ]);
});
