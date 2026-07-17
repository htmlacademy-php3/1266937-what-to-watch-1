<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

uses(RefreshDatabase::class);

it('registers a new user and returns authentication token', function () {
    Storage::fake('public');

    $avatar = UploadedFile::fake()->image('avatar.jpg')->size(500);

    $registerData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'file' => $avatar,
    ];

    $response = $this->postJson('/api/register', $registerData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'user' => ['name', 'email', 'file', 'role'],
                'token' => [
                    'user' => ['name', 'email', 'file', 'role'],
                    'token',
                ],
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});

it('returns 422 if email is already taken during registration', function () {
    User::factory()->create(['email' => 'duplicate@example.com']);

    $registerData = [
        'name' => 'Jane Doe',
        'email' => 'duplicate@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->postJson('/api/register', $registerData);

    $response->assertStatus(422);
});

it('logs in an existing user and returns authentication token', function () {
    User::factory()->create([
        'email' => 'login@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $loginData = [
        'email' => 'login@example.com',
        'password' => 'secret123',
    ];

    $response = $this->postJson('/api/login', $loginData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'user' => ['name', 'email', 'file', 'role'],
                'token',
            ],
        ]);
});

it('returns 422 for invalid login credentials', function () {
    User::factory()->create([
        'email' => 'wrong@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $invalidData = [
        'email' => 'wrong@example.com',
        'password' => 'incorrect_password',
    ];

    $response = $this->postJson('/api/login', $invalidData);

    $response->assertStatus(422);
});

it('logs out an authenticated user and revokes token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/logout');

    $response->assertStatus(204);

    expect($user->tokens()->count())->toBe(0);
});

it('denies a guest from logging out', function () {
    $response = $this->postJson('/api/logout');

    $response->assertStatus(401);
});
