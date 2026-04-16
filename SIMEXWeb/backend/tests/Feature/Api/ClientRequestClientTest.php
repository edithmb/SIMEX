<?php

use App\Models\ClientRequest;
use App\Models\Location;
use App\Models\User;

test('unauthenticated user cannot access client requests', function () {
    $this->getJson('/api/client-requests-client')->assertStatus(401);
});

test('client user can list their own requests', function () {
    [$user, $token] = createAuthenticatedUser();
    ClientRequest::factory()->count(2)->create(['created_by' => $user->id]);
    ClientRequest::factory()->create(); // another user's request

    $response = $this->getJson('/api/client-requests-client', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(2);
});

test('client user can create a request', function () {
    [$user, $token] = createAuthenticatedUser();
    $origin = Location::factory()->create();
    $destination = Location::factory()->create();

    $response = $this->postJson('/api/client-requests-client', [
        'volume_m3' => 25.50,
        'gross_weight_kg' => 1200.00,
        'comments' => 'Test request',
        'origin_id' => $origin->id,
        'destination_id' => $destination->id,
        'responsability' => 'BUYER',
    ], authHeaders($token));

    $response->assertStatus(201);
    expect(ClientRequest::first()->created_by)->toBe($user->id);
    expect(ClientRequest::first()->estado)->toBe('enviado');
});

test('index eager loads relationships', function () {
    [$user, $token] = createAuthenticatedUser();
    ClientRequest::factory()->create(['created_by' => $user->id]);

    $response = $this->getJson('/api/client-requests-client', authHeaders($token));

    $response->assertOk();
    $data = $response->json()[0];
    expect($data)->toHaveKeys(['client', 'origin', 'destination', 'commercial_offers']);
});
