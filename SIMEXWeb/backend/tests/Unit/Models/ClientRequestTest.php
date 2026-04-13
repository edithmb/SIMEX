<?php

use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\CommercialOffer;
use App\Models\Location;
use App\Models\User;
test('client request has correct fillable attributes', function () {
    $fillable = (new ClientRequest())->getFillable();

    expect($fillable)->toContain('client_id', 'volume_m3', 'gross_weight_kg', 'comments', 'origin_id', 'destination_id', 'created_by', 'estado');
});

test('client request has UPDATED_AT set to null', function () {
    expect(ClientRequest::UPDATED_AT)->toBeNull();
});

test('volume_m3 is cast to decimal', function () {
    $request = ClientRequest::factory()->create(['volume_m3' => 12.5]);

    expect($request->volume_m3)->toBe('12.50');
});

test('gross_weight_kg is cast to decimal', function () {
    $request = ClientRequest::factory()->create(['gross_weight_kg' => 500.1]);

    expect($request->gross_weight_kg)->toBe('500.10');
});

test('client request belongs to client', function () {
    $client = Client::factory()->create();
    $request = ClientRequest::factory()->create(['client_id' => $client->id]);

    expect($request->client)->toBeInstanceOf(Client::class);
    expect($request->client->id)->toBe($client->id);
});

test('client request belongs to origin location', function () {
    $location = Location::factory()->create();
    $request = ClientRequest::factory()->create(['origin_id' => $location->id]);

    expect($request->origin)->toBeInstanceOf(Location::class);
});

test('client request belongs to destination location', function () {
    $location = Location::factory()->create();
    $request = ClientRequest::factory()->create(['destination_id' => $location->id]);

    expect($request->destination)->toBeInstanceOf(Location::class);
});

test('client request belongs to creator', function () {
    $user = User::factory()->create();
    $request = ClientRequest::factory()->create(['created_by' => $user->id]);

    expect($request->creator)->toBeInstanceOf(User::class);
});

test('client request has many commercial offers', function () {
    $request = ClientRequest::factory()->create();
    CommercialOffer::factory()->count(2)->create(['client_request_id' => $request->id]);

    expect($request->commercialOffers)->toHaveCount(2);
});
