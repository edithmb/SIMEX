<?php

use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\CommercialOffer;
use App\Models\ContainerType;
use App\Models\Incoterm;
use App\Models\LogisticsOperation;
use App\Models\Port;
use App\Models\User;
test('commercial offer has correct fillable attributes', function () {
    $fillable = (new CommercialOffer())->getFillable();

    expect($fillable)->toContain('reference', 'client_request_id', 'client_id', 'price', 'status', 'origin_port_id', 'destination_port_id');
});

test('commercial offer defaults status to draft', function () {
    $offer = new CommercialOffer();

    expect($offer->status)->toBe('draft');
});

test('price is cast to decimal', function () {
    $offer = CommercialOffer::factory()->create(['price' => 1500.5]);

    expect($offer->price)->toBe('1500.50');
});

test('valid_until is cast to date', function () {
    $offer = CommercialOffer::factory()->create(['valid_until' => '2026-12-31']);

    expect($offer->valid_until)->toBeInstanceOf(\DateTimeInterface::class);
    expect($offer->valid_until->format('Y-m-d'))->toBe('2026-12-31');
});

test('scopeDraft filters by draft status', function () {
    CommercialOffer::factory()->create(['status' => 'draft']);
    CommercialOffer::factory()->create(['status' => 'accepted']);

    expect(CommercialOffer::draft()->count())->toBe(1);
});

test('scopeAccepted filters by accepted status', function () {
    CommercialOffer::factory()->create(['status' => 'draft']);
    CommercialOffer::factory()->create(['status' => 'accepted']);

    expect(CommercialOffer::accepted()->count())->toBe(1);
});

test('scopeRejected filters by rejected status', function () {
    CommercialOffer::factory()->create(['status' => 'rejected']);
    CommercialOffer::factory()->create(['status' => 'draft']);

    expect(CommercialOffer::rejected()->count())->toBe(1);
});

test('commercial offer belongs to client request', function () {
    $request = ClientRequest::factory()->create();
    $offer = CommercialOffer::factory()->create(['client_request_id' => $request->id]);

    expect($offer->clientRequest)->toBeInstanceOf(ClientRequest::class);
});

test('commercial offer belongs to client', function () {
    $client = Client::factory()->create();
    $offer = CommercialOffer::factory()->create(['client_id' => $client->id]);

    expect($offer->client)->toBeInstanceOf(Client::class);
});

test('commercial offer belongs to origin port', function () {
    $port = Port::factory()->create();
    $offer = CommercialOffer::factory()->create(['origin_port_id' => $port->id]);

    expect($offer->originPort)->toBeInstanceOf(Port::class);
});

test('commercial offer belongs to destination port', function () {
    $port = Port::factory()->create();
    $offer = CommercialOffer::factory()->create(['destination_port_id' => $port->id]);

    expect($offer->destinationPort)->toBeInstanceOf(Port::class);
});

test('commercial offer belongs to container type', function () {
    $type = ContainerType::factory()->create();
    $offer = CommercialOffer::factory()->create(['container_type_id' => $type->id]);

    expect($offer->containerType)->toBeInstanceOf(ContainerType::class);
});

test('commercial offer has one logistics operation', function () {
    $offer = CommercialOffer::factory()->create();
    LogisticsOperation::factory()->create(['commercial_offer_id' => $offer->id]);

    expect($offer->logisticsOperation)->toBeInstanceOf(LogisticsOperation::class);
});
