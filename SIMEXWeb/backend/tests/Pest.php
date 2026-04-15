<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function createAuthenticatedUser(array $attributes = []): array
{
    $user = \App\Models\User::factory()->create($attributes);
    $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

    return [$user, $token];
}

function authHeaders(string $token): array
{
    return ['Authorization' => "Bearer $token"];
}
