<?php

use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Interfaces\FilmRepositoryInterface;
use App\Jobs\ProcessFilm;
use App\Models\Role;
use App\Models\User;
use App\Models\Film;
use App\Enums\RoleName;

uses(RefreshDatabase::class);

it('asserts that correct film data is stored in the database', function () {
    $imdbId = 'tt32565993';

    $film = Film::factory()->create([
        'imdb_id' => $imdbId,
        'status' => 'pending',
    ]);

    $apiData = [
        'Title' => 'The Sheep Detectives',
        'Poster' => 'https://m.media-amazon.com/images/1',
        'Plot' => 'Every night a shepherd reads aloud a murder mystery, pretending ...',
        'Runtime' => '109 min',
        'Year' => '08 May 2026',
        'Genre' => 'Comedy, Family, Mystery',
        'Director' => 'Kyle Balda',
        'Actors' => 'Hugh Jackman, Brett Goldstein, Patrick Stewart',
        'imdbID' => $imdbId,
    ];

    $this->mock(FilmRepositoryInterface::class, fn($mock) =>
        $mock->shouldReceive('getFilmById')
            ->once()
            ->with($imdbId)
            ->andReturn($apiData));

    $this->app->call([new ProcessFilm($film->imdb_id), 'handle']);

    $this->assertDatabaseHas('films', [
        'imdb_id' => $imdbId,
        'name' => 'The Sheep Detectives',
        'poster_image' => 'https://m.media-amazon.com/images/1',
        'description' => 'Every night a shepherd reads aloud a murder mystery, pretending ...',
        'run_time' => 109,
        'released' => 2026,
        'status' => 'on moderation',
    ]);
});

it('asserts that the correct job is dispatched to the queue', function () {
    Queue::fake();

    $moderator = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => RoleName::Moderator->value])->id,
    ]);

    $imdbId = 'tt32565993';

    $response = $this->actingAs($moderator)->post('/api/films', ['imdb_id' => $imdbId]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('films', [
        'imdb_id' => $imdbId,
        'status' => 'pending',
    ]);

    Queue::assertPushed(ProcessFilm::class);
});
