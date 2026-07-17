<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use App\Models\Film;
use App\Models\User;
use App\Models\Role;
use App\Enums\RoleName;
use App\Enums\FilmStatus;

uses(RefreshDatabase::class);

beforeEach(fn() => Cache::flush());

it('shows current promo film', function () {
    $promoFilm = Film::factory()->create([
        'status' => FilmStatus::Ready->value,
        'is_promo' => true,
    ]);

    Film::factory()->create(['status' => FilmStatus::Ready->value, 'is_promo' => false]);

    $response = $this->getJson('/api/promo');

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $promoFilm->id)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'poster_image',
                'preview_image',
                'background_image',
                'background_color',
                'video_link',
                'preview_video_link',
                'description',
                'rating',
                'scores_count',
                'directors',
                'starring',
                'run_time',
                'genres',
                'released',
                'is_favorite',
            ],
        ]);
});

it('denies guest from setting promo film', function () {
    $film = Film::factory()->create();

    $response = $this->postJson("/api/promo/{$film->id}");

    $response->assertStatus(401);
});

it('denies regular user from setting promo film', function () {
    $user = User::factory()->create();
    $film = Film::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->postJson("/api/promo/{$film->id}");

    $response->assertStatus(403);
});

it('allows moderator to set promo film', function () {
    $moderator = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => RoleName::Moderator->value])->id
    ]);

    $oldPromo = Film::factory()->create(['is_promo' => true]);
    $newPromo = Film::factory()->create(['is_promo' => false]);

    $response = $this->actingAs($moderator, 'sanctum')
        ->postJson("/api/promo/{$newPromo->id}");

    $response->assertOk();

    $this->assertDatabaseHas('films', [
        'id' => $newPromo->id,
        'is_promo' => true,
    ]);

    $this->assertDatabaseHas('films', [
        'id' => $oldPromo->id,
        'is_promo' => false,
    ]);
});

it('returns 404 if promo film is not found', function () {
    $moderator = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => RoleName::Moderator->value])->id
    ]);

    $response = $this->actingAs($moderator)
        ->postJson('/api/promo/999999');

    $response->assertStatus(404);
});
