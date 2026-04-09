<?php

use App\Models\Client;
use App\Models\CommercialOffer;
use App\Models\LogisticsOperation;
test('logistics operation has correct fillable attributes', function () {
    $fillable = (new LogisticsOperation())->getFillable();

    expect($fillable)->toContain('reference', 'commercial_offer_id', 'client_id', 'status', 'etd', 'eta', 'atd', 'ata', 'completed_at');
});

test('logistics operation defaults status to preparation', function () {
    $op = new LogisticsOperation();

    expect($op->status)->toBe('preparation');
});

test('date fields are cast correctly', function () {
    $op = LogisticsOperation::factory()->create([
        'etd' => '2026-06-01',
        'eta' => '2026-07-01',
    ]);

    expect($op->etd)->toBeInstanceOf(\DateTimeInterface::class);
    expect($op->eta)->toBeInstanceOf(\DateTimeInterface::class);
});

test('scopeInPreparation filters by preparation status', function () {
    LogisticsOperation::factory()->create(['status' => 'preparation']);
    LogisticsOperation::factory()->create(['status' => 'completed', 'completed_at' => now()]);

    expect(LogisticsOperation::inPreparation()->count())->toBe(1);
});

test('scopeCompleted filters by completed status with completed_at', function () {
    LogisticsOperation::factory()->create(['status' => 'completed', 'completed_at' => now()]);
    LogisticsOperation::factory()->create(['status' => 'completed', 'completed_at' => null]);
    LogisticsOperation::factory()->create(['status' => 'preparation']);

    expect(LogisticsOperation::completed()->count())->toBe(1);
});

test('scopeByStatus filters by given status', function () {
    LogisticsOperation::factory()->create(['status' => 'preparation']);
    LogisticsOperation::factory()->create(['status' => 'in_transit']);
    LogisticsOperation::factory()->create(['status' => 'in_transit']);

    expect(LogisticsOperation::byStatus('in_transit')->count())->toBe(2);
});

test('logistics operation belongs to commercial offer', function () {
    $offer = CommercialOffer::factory()->create();
    $op = LogisticsOperation::factory()->create(['commercial_offer_id' => $offer->id]);

    expect($op->commercialOffer)->toBeInstanceOf(CommercialOffer::class);
});

test('logistics operation belongs to client', function () {
    $client = Client::factory()->create();
    $op = LogisticsOperation::factory()->create(['client_id' => $client->id]);

    expect($op->client)->toBeInstanceOf(Client::class);
});
