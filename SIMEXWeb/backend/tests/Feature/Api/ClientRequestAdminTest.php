<?php

use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\Location;

test('unauthenticated user cannot access admin requests', function () {
    $this->getJson('/api/client-requests-admin')->assertStatus(401);
});

test('admin can list all requests', function () {
    [$user, $token] = createAuthenticatedUser();
    ClientRequest::factory()->count(3)->create();

    $response = $this->getJson('/api/client-requests-admin', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(3);
});

test('admin can create a request with client_id', function () {
    [$user, $token] = createAuthenticatedUser();
    $client = Client::factory()->create();
    $origin = Location::factory()->create();
    $destination = Location::factory()->create();

    $response = $this->postJson('/api/client-requests-admin', [
        'client_id' => $client->id,
        'volume_m3' => 50.00,
        'gross_weight_kg' => 3000.00,
        'comments' => 'Admin request',
        'origin_id' => $origin->id,
        'destination_id' => $destination->id,
        'responsability' => 'SELLER',
    ], authHeaders($token));

    $response->assertStatus(201);
    expect(ClientRequest::first()->created_by)->toBe($user->id);
    expect(ClientRequest::first()->client_id)->toBe($client->id);
    expect(ClientRequest::first()->estado)->toBe('enviado');
});

test('index returns requests with relationships', function () {
    [$user, $token] = createAuthenticatedUser();
    ClientRequest::factory()->create();

    $response = $this->getJson('/api/client-requests-admin', authHeaders($token));

    $response->assertOk();
    expect($response->json()[0])->toHaveKeys(['client', 'origin', 'destination', 'commercial_offers']);
});
