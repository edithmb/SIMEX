<?php

use App\Models\City;
use App\Models\ShippingLine;

test('unauthenticated user cannot access shipping lines', function () {
    $this->getJson('/api/shipping-lines')->assertStatus(401);
});

test('authenticated user can list shipping lines', function () {
    [$user, $token] = createAuthenticatedUser();
    ShippingLine::factory()->count(2)->create();

    $response = $this->getJson('/api/shipping-lines', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(2);
});

test('authenticated user can create a shipping line', function () {
    [$user, $token] = createAuthenticatedUser();
    $city = City::factory()->create();

    $this->postJson('/api/shipping-lines', [
        'name' => 'Maersk',
        'city_id' => $city->id,
    ], authHeaders($token))->assertStatus(201);
});

test('create shipping line fails without name', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/shipping-lines', [], authHeaders($token))
        ->assertStatus(422)
        ->assertJsonValidationErrors('name');
});

test('authenticated user can update a shipping line', function () {
    [$user, $token] = createAuthenticatedUser();
    $sl = ShippingLine::factory()->create();

    $this->putJson("/api/shipping-lines/{$sl->id}", [
        'name' => 'Updated',
        'city_id' => $sl->city_id,
    ], authHeaders($token))->assertOk();
});

test('authenticated user can delete a shipping line', function () {
    [$user, $token] = createAuthenticatedUser();
    $sl = ShippingLine::factory()->create();

    $this->deleteJson("/api/shipping-lines/{$sl->id}", [], authHeaders($token))->assertStatus(204);
});
