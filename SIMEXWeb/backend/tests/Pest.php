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

function createAuthenticatedUser(array $attributes = [], string $role = 'admin'): array
{
    $roleModel = \App\Models\Role::firstOrCreate(
        ['name' => $role],
        ['description' => ucfirst($role)]
    );

    $user = \App\Models\User::factory()->create(array_merge(
        ['role_id' => $roleModel->id],
        $attributes
    ));
    $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

    return [$user, $token];
}

function authHeaders(string $token): array
{
    return ['Authorization' => "Bearer $token"];
}
