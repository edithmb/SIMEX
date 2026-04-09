<?php

use App\Models\City;
use App\Models\Port;

test('unauthenticated user cannot access ports', function () {
    $this->getJson('/api/ports')->assertStatus(401);
});

test('authenticated user can list ports with city', function () {
    [$user, $token] = createAuthenticatedUser();
    Port::factory()->count(2)->create();

    $response = $this->getJson('/api/ports', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(2);
    expect($response->json()[0])->toHaveKey('city');
});

test('authenticated user can create a port', function () {
    [$user, $token] = createAuthenticatedUser();
    $city = City::factory()->create();

    $response = $this->postJson('/api/ports', [
        'name' => 'Port of Barcelona',
        'city_id' => $city->id,
    ], authHeaders($token));

    $response->assertStatus(201);
});

test('create port fails without city_id', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/ports', ['name' => 'Test'], authHeaders($token))
        ->assertStatus(422)
        ->assertJsonValidationErrors('city_id');
});

test('authenticated user can update a port', function () {
    [$user, $token] = createAuthenticatedUser();
    $port = Port::factory()->create();

    $this->putJson("/api/ports/{$port->id}", [
        'name' => 'Updated',
        'city_id' => $port->city_id,
    ], authHeaders($token))->assertOk();
});

test('authenticated user can delete a port', function () {
    [$user, $token] = createAuthenticatedUser();
    $port = Port::factory()->create();

    $this->deleteJson("/api/ports/{$port->id}", [], authHeaders($token))->assertStatus(204);
});
