<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

uses(RefreshDatabase::class);

it('shows user profile data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->getJson('/api/user');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'file',
                'role',
            ],
        ])
        ->assertJsonPath('data.email', $user->email);
});

it('denies a guest from viewing user profile', function () {
    $this->getJson('/api/user')->assertStatus(401);
});

it('allows an authenticated user to update profile data', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $newAvatar = UploadedFile::fake()->image('new_avatar.jpg')->size(500);

    $updateData = [
        'name' => 'New Name',
        'email' => 'new@example.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123', // Добавляем подтверждение для прохождения валидации
        'file' => $newAvatar,
    ];

    $response = $this->actingAs($user)
        ->patchJson('/api/user', $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'file',
                'role',
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => $updateData['name'],
        'email' => $updateData['email'],
    ]);

    $user->refresh();
    $this->assertNotNull($user->file);
    Storage::disk('public')->assertExists($user->file);
});

it('denies a guest from updating user profile', function () {
    $this->patchJson('/api/user', ['name' => 'New Name'])->assertStatus(401);
});
