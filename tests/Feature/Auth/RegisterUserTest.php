<?php

use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('can register a user and assign 100 welcome points', function () {
    $userData = [
        'name' => 'Ali Alavi',
        'email' => 'ali@example.com',
        'password' => 'password123',
    ];

    $response = postJson('/api/register', $userData);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'message' => 'User registered successfully with 100 welcome points!',
            'points' => 100
        ]);

    assertDatabaseHas('users', [
        'email' => 'ali@example.com',
        'points_balance' => 100,
    ]);

    $user = User::where('email', 'ali@example.com')->first();
    assertDatabaseHas('point_logs', [
        'user_id' => $user->id,
        'points' => 100,
        'reason' => 'Welcome Bonus',
    ]);
});

it('requires a name, email, and password for registration', function () {
    postJson('/api/register', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});