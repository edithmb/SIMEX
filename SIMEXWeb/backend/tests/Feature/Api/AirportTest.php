<?php

use App\Models\Airport;
use App\Models\City;

test('unauthenticated user cannot access airports', function () {
    $this->getJson('/api/airports')->assertStatus(401);
});

test('authenticated user can list airports with city', function () {
    [$user, $token] = createAuthenticatedUser();
    Airport::factory()->count(2)->create();

    $response = $this->getJson('/api/airports', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(2);
});

test('authenticated user can create an airport', function () {
    [$user, $token] = createAuthenticatedUser();
    $city = City::factory()->create();

    $response = $this->postJson('/api/airports', [
        'code' => 'BCN',
        'name' => 'El Prat',
        'city_id' => $city->id,
    ], authHeaders($token));

    $response->assertStatus(201);
});

test('create airport fails without required fields', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/airports', [], authHeaders($token))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['code', 'name', 'city_id']);
});

test('authenticated user can update an airport', function () {
    [$user, $token] = createAuthenticatedUser();
    $airport = Airport::factory()->create();

    $this->putJson("/api/airports/{$airport->id}", [
        'code' => 'MAD',
        'name' => 'Barajas',
        'city_id' => $airport->city_id,
    ], authHeaders($token))->assertOk();
});

test('authenticated user can delete an airport', function () {
    [$user, $token] = createAuthenticatedUser();
    $airport = Airport::factory()->create();

    $this->deleteJson("/api/airports/{$airport->id}", [], authHeaders($token))->assertStatus(204);
});
