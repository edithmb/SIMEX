<?php

use App\Models\Airport;
use App\Models\Carrier;
use App\Models\City;
use App\Models\ContainerType;
use App\Models\Country;
use App\Models\Incoterm;
use App\Models\IncotermType;
use App\Models\Location;
use App\Models\LoginSession;
use App\Models\Port;
use App\Models\Role;
use App\Models\ShippingLine;
use App\Models\TrackingStep;
use App\Models\User;
// --- Country ---

test('country has correct fillable and no timestamps', function () {
    $country = new Country();
    expect($country->getFillable())->toContain('name');
    expect($country->timestamps)->toBeFalse();
});

test('country has many cities', function () {
    $country = Country::factory()->create();
    City::factory()->count(2)->create(['country_id' => $country->id]);

    expect($country->cities)->toHaveCount(2);
});

// --- City ---

test('city has correct fillable and no timestamps', function () {
    $city = new City();
    expect($city->getFillable())->toContain('name', 'country_id');
    expect($city->timestamps)->toBeFalse();
});

test('city belongs to country', function () {
    $city = City::factory()->create();

    expect($city->country)->toBeInstanceOf(Country::class);
});

test('city has many ports and airports', function () {
    $city = City::factory()->create();
    Port::factory()->count(2)->create(['city_id' => $city->id]);
    Airport::factory()->create(['city_id' => $city->id]);

    expect($city->ports)->toHaveCount(2);
    expect($city->airports)->toHaveCount(1);
});

test('city has many carriers and shipping lines', function () {
    $city = City::factory()->create();
    Carrier::factory()->create(['city_id' => $city->id]);
    ShippingLine::factory()->create(['city_id' => $city->id]);

    expect($city->carriers)->toHaveCount(1);
    expect($city->shippingLines)->toHaveCount(1);
});

// --- Port ---

test('port has correct fillable and no timestamps', function () {
    $port = new Port();
    expect($port->getFillable())->toContain('name', 'city_id');
    expect($port->timestamps)->toBeFalse();
});

test('port belongs to city', function () {
    $port = Port::factory()->create();

    expect($port->city)->toBeInstanceOf(City::class);
});

// --- Airport ---

test('airport has correct fillable and no timestamps', function () {
    $airport = new Airport();
    expect($airport->getFillable())->toContain('code', 'name', 'city_id');
    expect($airport->timestamps)->toBeFalse();
});

test('airport belongs to city', function () {
    $airport = Airport::factory()->create();

    expect($airport->city)->toBeInstanceOf(City::class);
});

// --- ShippingLine ---

test('shipping line has correct fillable and no timestamps', function () {
    $sl = new ShippingLine();
    expect($sl->getFillable())->toContain('name', 'city_id');
    expect($sl->timestamps)->toBeFalse();
});

test('shipping line belongs to city', function () {
    $sl = ShippingLine::factory()->create();

    expect($sl->city)->toBeInstanceOf(City::class);
});

// --- Carrier ---

test('carrier has correct fillable and no timestamps', function () {
    $carrier = new Carrier();
    expect($carrier->getFillable())->toContain('name', 'city_id');
    expect($carrier->timestamps)->toBeFalse();
});

test('carrier belongs to city', function () {
    $carrier = Carrier::factory()->create();

    expect($carrier->city)->toBeInstanceOf(City::class);
});

// --- ContainerType ---

test('container type has correct fillable and no timestamps', function () {
    $ct = new ContainerType();
    expect($ct->getFillable())->toContain('type_name');
    expect($ct->timestamps)->toBeFalse();
});

// --- Role ---

test('role has correct fillable and no timestamps', function () {
    $role = new Role();
    expect($role->getFillable())->toContain('name', 'description');
    expect($role->timestamps)->toBeFalse();
});

test('role has many users', function () {
    $role = Role::factory()->create();
    User::factory()->count(2)->create(['role_id' => $role->id]);

    expect($role->users)->toHaveCount(2);
});

// --- IncotermType ---

test('incoterm type has correct fillable and no timestamps', function () {
    $it = new IncotermType();
    expect($it->getFillable())->toContain('code', 'name');
    expect($it->timestamps)->toBeFalse();
});

test('incoterm type has many incoterms', function () {
    $it = IncotermType::factory()->create();
    Incoterm::factory()->count(2)->create(['incoterm_type_id' => $it->id]);

    expect($it->incoterms)->toHaveCount(2);
});

// --- TrackingStep ---

test('tracking step has correct fillable and no timestamps', function () {
    $ts = new TrackingStep();
    expect($ts->getFillable())->toContain('name');
    expect($ts->timestamps)->toBeFalse();
});

// --- Incoterm ---

test('incoterm has correct fillable and no timestamps', function () {
    $inc = new Incoterm();
    expect($inc->getFillable())->toContain('incoterm_type_id', 'tracking_step_id', 'order_num');
    expect($inc->timestamps)->toBeFalse();
});

test('incoterm belongs to incoterm type and tracking step', function () {
    $inc = Incoterm::factory()->create();

    expect($inc->incotermType)->toBeInstanceOf(IncotermType::class);
    expect($inc->trackingStep)->toBeInstanceOf(TrackingStep::class);
});

// --- LoginSession ---

test('login session has correct fillable and no timestamps', function () {
    $ls = new LoginSession();
    expect($ls->getFillable())->toContain('user_id', 'ip_address', 'user_agent', 'device_type');
    expect($ls->timestamps)->toBeFalse();
});

test('login session casts datetime fields', function () {
    $ls = LoginSession::factory()->create();

    expect($ls->logged_in_at)->toBeInstanceOf(\DateTimeInterface::class);
});

test('login session belongs to user', function () {
    $ls = LoginSession::factory()->create();

    expect($ls->user)->toBeInstanceOf(User::class);
});

// --- Location ---

test('location has correct fillable and no timestamps', function () {
    $loc = new Location();
    expect($loc->getFillable())->toContain('name', 'client_id', 'latitude', 'longitude', 'city_id');
    expect($loc->timestamps)->toBeFalse();
});

test('location belongs to client and city', function () {
    $loc = Location::factory()->create();

    expect($loc->client)->toBeInstanceOf(\App\Models\Client::class);
    expect($loc->city)->toBeInstanceOf(City::class);
});

test('location casts coordinates to decimal', function () {
    $loc = Location::factory()->create(['latitude' => 41.38879, 'longitude' => 2.15899]);

    expect($loc->latitude)->toBe('41.38879000');
    expect($loc->longitude)->toBe('2.15899000');
});
