<?php

use App\Models\ContainerType;

test('unauthenticated user cannot access container types', function () {
    $this->getJson('/api/container-types')->assertStatus(401);
});

test('authenticated user can list container types', function () {
    [$user, $token] = createAuthenticatedUser();
    ContainerType::factory()->count(2)->create();

    $response = $this->getJson('/api/container-types', authHeaders($token));

    $response->assertOk();
    expect($response->json())->toHaveCount(2);
});

test('authenticated user can create a container type', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/container-types', [
        'type_name' => '20GP',
    ], authHeaders($token))->assertStatus(201);
});

test('create container type fails without type_name', function () {
    [$user, $token] = createAuthenticatedUser();

    $this->postJson('/api/container-types', [], authHeaders($token))
        ->assertStatus(422)
        ->assertJsonValidationErrors('type_name');
});

test('authenticated user can update a container type', function () {
    [$user, $token] = createAuthenticatedUser();
    $ct = ContainerType::factory()->create();

    $this->putJson("/api/container-types/{$ct->id}", [
        'type_name' => '40HC',
    ], authHeaders($token))->assertOk();
});

test('authenticated user can delete a container type', function () {
    [$user, $token] = createAuthenticatedUser();
    $ct = ContainerType::factory()->create();

    $this->deleteJson("/api/container-types/{$ct->id}", [], authHeaders($token))->assertStatus(204);
});
