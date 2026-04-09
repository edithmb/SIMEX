<?php

use App\Models\Carrier;
use App\Models\City;

test('unauthenticated user cannot access carriers', function () {
    $this->getJson('/api/carriers')->assertStatus(401);
});

test('authenticated user can list carriers', function () {
    [$user, $token] = createAuthenticatedUser();
    Carrier::factory()->count(2)->create();

    $response = $this->getJson('/api/carriers', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(2);
});

test('authenticated user can create a carrier', function () {
    [$user, $token] = createAuthenticatedUser();
    $city = City::factory()->create();

    $this->postJson('/api/carriers', [
        'name' => 'DHL',
        'city_id' => $city->id,
    ], authHeaders($token))->assertStatus(201);
});

test('create carrier fails without name', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/carriers', [], authHeaders($token))
        ->assertStatus(422)
        ->assertJsonValidationErrors('name');
});

test('authenticated user can update a carrier', function () {
    [$user, $token] = createAuthenticatedUser();
    $carrier = Carrier::factory()->create();

    $this->putJson("/api/carriers/{$carrier->id}", [
        'name' => 'Updated',
        'city_id' => $carrier->city_id,
    ], authHeaders($token))->assertOk();
});

test('authenticated user can delete a carrier', function () {
    [$user, $token] = createAuthenticatedUser();
    $carrier = Carrier::factory()->create();

    $this->deleteJson("/api/carriers/{$carrier->id}", [], authHeaders($token))->assertStatus(204);
});
