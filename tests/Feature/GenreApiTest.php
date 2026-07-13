<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Genre;
use App\Models\User;
use App\Models\Role;
use App\Enums\RoleName;

uses(RefreshDatabase::class);

it('shows all genres', function () {
    Genre::factory()->create(['name' => 'Comedy']);
    Genre::factory()->create(['name' => 'Action']);
    Genre::factory()->create(['name' => 'Horror']);

    $response = $this->getJson('/api/genres');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name'],
            ],
        ]);
});

it('denies a guest from updating genre', function () {
    $genre = Genre::factory()->create();

    $response = $this->patchJson("/api/genres/{$genre->id}", [
        'name' => 'Sci-Fi',
    ]);

    $response->assertStatus(401);
});

it('denies a regular user from updating genre', function () {
    $user = User::factory()->create();
    $genre = Genre::factory()->create();

    $response = $this->actingAs($user)
        ->patchJson("/api/genres/{$genre->id}", [
            'name' => 'Sci-Fi',
        ]);

    $response->assertStatus(403);
});

it('allows a moderator to update genre', function () {
    User::factory()->create();

    $moderator = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => RoleName::Moderator->value])->id
    ]);

    $genre = Genre::factory()->create(['name' => 'Old Name']);
    $updateData = ['name' => 'Sci-Fi'];

    $response = $this->actingAs($moderator)
        ->patchJson("/api/genres/{$genre->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);

    $this->assertDatabaseHas('genres', [
        'id' => $genre->id,
        'name' => $updateData['name'],
    ]);
});

it('returns 404 if a genre is not found when updating', function () {
    User::factory()->create();

    $moderator = User::factory()->create([
        'role_id' => Role::firstOrCreate(['name' => RoleName::Moderator->value])->id
    ]);

    $response = $this->actingAs($moderator)
        ->patchJson('/api/genres/999999', [
            'name' => 'Sci-Fi',
        ]);

    $response->assertStatus(404);
});
