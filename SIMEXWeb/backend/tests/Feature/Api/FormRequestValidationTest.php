<?php

use App\Models\City;
use App\Models\Country;

test('StoreCountryRequest requires name', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/countries', [], authHeaders($token))
        ->assertJsonValidationErrors('name');
});

test('StoreCountryRequest name max 50 characters', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/countries', ['name' => str_repeat('a', 51)], authHeaders($token))
        ->assertJsonValidationErrors('name');
});

test('StoreCityRequest requires name and country_id', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/cities', [], authHeaders($token))
        ->assertJsonValidationErrors(['name', 'country_id']);
});

test('StoreCityRequest country_id must exist', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/cities', ['name' => 'Test', 'country_id' => 9999], authHeaders($token))
        ->assertJsonValidationErrors('country_id');
});

test('StorePortRequest requires name and city_id', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/ports', [], authHeaders($token))
        ->assertJsonValidationErrors(['name', 'city_id']);
});

test('StoreAirportRequest requires code name and city_id', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/airports', [], authHeaders($token))
        ->assertJsonValidationErrors(['code', 'name', 'city_id']);
});

test('StoreAirportRequest code max 5 characters', function () {
    [$user, $token] = createAuthenticatedUser();
    $city = City::factory()->create();

    $this->postJson('/api/airports', [
        'code' => 'TOOLONG',
        'name' => 'Test',
        'city_id' => $city->id,
    ], authHeaders($token))->assertJsonValidationErrors('code');
});

test('StoreShippingLineRequest requires name and city_id', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/shipping-lines', [], authHeaders($token))
        ->assertJsonValidationErrors(['name', 'city_id']);
});

test('StoreCarrierRequest requires name and city_id', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/carriers', [], authHeaders($token))
        ->assertJsonValidationErrors(['name', 'city_id']);
});

test('StoreContainerTypeRequest requires type_name', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/container-types', [], authHeaders($token))
        ->assertJsonValidationErrors('type_name');
});

test('StoreContainerTypeRequest type_name max 50 characters', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/container-types', ['type_name' => str_repeat('a', 51)], authHeaders($token))
        ->assertJsonValidationErrors('type_name');
});
