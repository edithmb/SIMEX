<?php

use App\Models\Location;

test('unauthenticated user cannot access locations', function () {
    $this->getJson('/api/locations')->assertStatus(401);
});

test('authenticated user can list locations', function () {
    [$user, $token] = createAuthenticatedUser();
    Location::factory()->count(3)->create();

    $response = $this->getJson('/api/locations', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(3);
});

test('locations list returns only id and name', function () {
    [$user, $token] = createAuthenticatedUser();
    Location::factory()->create(['name' => 'Warehouse A']);

    $response = $this->getJson('/api/locations', authHeaders($token));

    $response->assertOk();
    expect($response->json()[0])->toHaveKeys(['id', 'name']);
});
