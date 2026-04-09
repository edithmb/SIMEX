<?php

use App\Models\Client;

test('unauthenticated user cannot access clients', function () {
    $this->getJson('/api/clients')->assertStatus(401);
});

test('authenticated user can list clients', function () {
    [$user, $token] = createAuthenticatedUser();
    Client::factory()->count(3)->create();

    $response = $this->getJson('/api/clients', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(3);
});

test('clients list returns only id and company_name', function () {
    [$user, $token] = createAuthenticatedUser();
    Client::factory()->create(['company_name' => 'ACME Corp']);

    $response = $this->getJson('/api/clients', authHeaders($token));

    $response->assertOk();
    expect($response->json()[0])->toHaveKeys(['id', 'company_name']);
});
