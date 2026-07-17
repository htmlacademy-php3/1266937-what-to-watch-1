<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Film;
use App\Models\Genre;
use App\Enums\FilmStatus;

uses(RefreshDatabase::class);

it('shows similar films with limit of 4', function () {
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);
    $genre = Genre::factory()->create();
    $film->genres()->attach($genre);

    $similarFilms = Film::factory()->count(6)->create(['status' => FilmStatus::Ready->value]);
    foreach ($similarFilms as $similarFilm) {
        $similarFilm->genres()->attach($genre);
    }

    $response = $this->getJson("/api/films/{$film->id}/similar");

    $response->assertStatus(200)
        ->assertJsonCount(4, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'preview_image',
                    'preview_video_link',
                ],
            ],
        ]);
});

it('shows all similar films if count is less than four', function () {
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);
    $genre = Genre::factory()->create();
    $film->genres()->attach($genre);

    $similarFilms = Film::factory()->count(2)->create(['status' => FilmStatus::Ready->value]);
    foreach ($similarFilms as $similarFilm) {
        $similarFilm->genres()->attach($genre);
    }

    $response = $this->getJson("/api/films/{$film->id}/similar");

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

it('matches similar films by genre', function () {
    $film = Film::factory()->create(['status' => FilmStatus::Ready->value]);
    $comedy = Genre::factory()->create(['name' => 'Comedy']);
    $film->genres()->attach($comedy);

    $similarFilm = Film::factory()->create(['status' => FilmStatus::Ready->value]);
    $similarFilm->genres()->attach($comedy);

    $otherFilm = Film::factory()->create(['status' => FilmStatus::Ready->value]);
    $horror = Genre::factory()->create(['name' => 'Horror']);
    $otherFilm->genres()->attach($horror);

    $response = $this->getJson("/api/films/{$film->id}/similar");

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['id' => $similarFilm->id]);
});

it('returns 404 if a film is not found', function () {
    $response = $this->getJson('/api/films/999999/similar');

    $response->assertStatus(404);
});
