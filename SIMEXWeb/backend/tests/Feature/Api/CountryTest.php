<?php

use App\Models\Country;

test('unauthenticated user cannot access countries', function () {
    $this->getJson('/api/countries')->assertStatus(401);
});

test('authenticated user can list countries', function () {
    [$user, $token] = createAuthenticatedUser();
    Country::factory()->count(3)->create();

    $response = $this->getJson('/api/countries', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(3);
});

test('authenticated user can create a country', function () {
    [$user, $token] = createAuthenticatedUser();

    $response = $this->postJson('/api/countries', ['name' => 'Spain'], authHeaders($token));

    $response->assertStatus(201)
        ->assertJson(['name' => 'Spain']);
});

test('create country fails validation without name', function () {
    [$user, $token] = createAuthenticatedUser();

    $response = $this->postJson('/api/countries', [], authHeaders($token));

    $response->assertStatus(422)
        ->assertJsonValidationErrors('name');
});

test('authenticated user can update a country', function () {
    [$user, $token] = createAuthenticatedUser();
    $country = Country::factory()->create(['name' => 'Old']);

    $response = $this->putJson("/api/countries/{$country->id}", ['name' => 'Updated'], authHeaders($token));

    $response->assertOk()
        ->assertJson(['name' => 'Updated']);
});

test('authenticated user can delete a country', function () {
    [$user, $token] = createAuthenticatedUser();
    $country = Country::factory()->create();

    $response = $this->deleteJson("/api/countries/{$country->id}", [], authHeaders($token));

    $response->assertStatus(204);
    expect(Country::find($country->id))->toBeNull();
});
