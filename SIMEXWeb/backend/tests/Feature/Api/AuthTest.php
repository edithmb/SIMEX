<?php

use App\Models\User;

test('user can login with valid credentials', function () {
    $user = User::factory()->create(['password_hash' => bcrypt('password')]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['token', 'token_type', 'expires_in'])
        ->assertJson(['token_type' => 'bearer']);
});

test('login fails with invalid email', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'nonexistent@test.com',
        'password' => 'password',
    ]);

    $response->assertStatus(422);
});

test('login fails with invalid password', function () {
    $user = User::factory()->create(['password_hash' => bcrypt('password')]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('email');
});

test('login fails for inactive user', function () {
    $user = User::factory()->inactive()->create(['password_hash' => bcrypt('password')]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(422);
});

test('login requires email and password', function () {
    $response = $this->postJson('/api/login', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

test('me endpoint returns authenticated user with role', function () {
    [$user, $token] = createAuthenticatedUser();

    $response = $this->getJson('/api/me', authHeaders($token));

    $response->assertOk()
        ->assertJsonStructure(['id', 'email', 'role']);
});
