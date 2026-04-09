<?php

use App\Models\City;
use App\Models\Country;

test('unauthenticated user cannot access cities', function () {
    $this->getJson('/api/cities')->assertStatus(401);
});

test('authenticated user can list cities with country', function () {
    [$user, $token] = createAuthenticatedUser();
    City::factory()->count(2)->create();

    $response = $this->getJson('/api/cities', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(2);
    expect($response->json()[0])->toHaveKey('country');
});

test('authenticated user can create a city', function () {
    [$user, $token] = createAuthenticatedUser();
    $country = Country::factory()->create();

    $response = $this->postJson('/api/cities', [
        'name' => 'Barcelona',
        'country_id' => $country->id,
    ], authHeaders($token));

    $response->assertStatus(201)
        ->assertJson(['name' => 'Barcelona']);
});

test('create city fails without country_id', function () {
    [$user, $token] = createAuthenticatedUser();

    $response = $this->postJson('/api/cities', ['name' => 'Test'], authHeaders($token));

    $response->assertStatus(422)
        ->assertJsonValidationErrors('country_id');
});

test('authenticated user can update a city', function () {
    [$user, $token] = createAuthenticatedUser();
    $city = City::factory()->create();

    $response = $this->putJson("/api/cities/{$city->id}", [
        'name' => 'Updated',
        'country_id' => $city->country_id,
    ], authHeaders($token));

    $response->assertOk();
});

test('authenticated user can delete a city', function () {
    [$user, $token] = createAuthenticatedUser();
    $city = City::factory()->create();

    $this->deleteJson("/api/cities/{$city->id}", [], authHeaders($token))->assertStatus(204);
});
