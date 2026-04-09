<?php

use App\Models\Client;
use App\Models\User;
use App\Models\Location;
use App\Models\ClientRequest;
use App\Models\CommercialOffer;
use App\Models\LogisticsOperation;
test('client has correct fillable attributes', function () {
    $fillable = (new Client())->getFillable();

    expect($fillable)->toContain('company_name', 'vat_number', 'address', 'country', 'postal_code', 'contact_name', 'email', 'phone');
});

test('client uses soft deletes', function () {
    $client = Client::factory()->create();
    $client->delete();

    expect($client->trashed())->toBeTrue();
});

test('client has timestamps disabled', function () {
    expect((new Client())->timestamps)->toBeFalse();
});

test('client belongs to creator', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['created_by' => $user->id]);

    expect($client->creator)->toBeInstanceOf(User::class);
    expect($client->creator->id)->toBe($user->id);
});

test('client belongs to updater', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['updated_by' => $user->id]);

    expect($client->updater)->toBeInstanceOf(User::class);
});

test('client belongs to deleter', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['deleted_by' => $user->id]);

    expect($client->deleter)->toBeInstanceOf(User::class);
});

test('client has many users', function () {
    $client = Client::factory()->create();
    User::factory()->count(2)->create(['client_id' => $client->id]);

    expect($client->users)->toHaveCount(2);
});

test('client has many locations', function () {
    $client = Client::factory()->create();
    Location::factory()->count(2)->create(['client_id' => $client->id]);

    expect($client->locations)->toHaveCount(2);
});

test('client has many client requests', function () {
    $client = Client::factory()->create();
    ClientRequest::factory()->count(2)->create(['client_id' => $client->id]);

    expect($client->clientRequests)->toHaveCount(2);
});

test('client has many commercial offers', function () {
    $client = Client::factory()->create();
    CommercialOffer::factory()->count(2)->create(['client_id' => $client->id]);

    expect($client->commercialOffers)->toHaveCount(2);
});
