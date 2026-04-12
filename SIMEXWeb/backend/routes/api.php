<?php

use App\Http\Controllers\AirportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientRequestAdminController;
use App\Http\Controllers\ClientRequestClientController;
use App\Http\Controllers\ComercialOfferController;
use App\Http\Controllers\ContainerTypeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\IncotermController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ShippingLineController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('countries',       CountryController::class)->except('show');
    Route::apiResource('cities',          CityController::class)->except('show');
    Route::apiResource('ports',           PortController::class)->except('show');
    Route::apiResource('airports',        AirportController::class)->except('show');
    Route::apiResource('shipping-lines',  ShippingLineController::class)->except('show');
    Route::apiResource('carriers',        CarrierController::class)->except('show');
    Route::apiResource('container-types', ContainerTypeController::class)->except('show');
    Route::get('/locations', [LocationController::class, 'index']);
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/incoterms', [IncotermController::class, 'index']);
    Route::post('/commercial-offers', [ComercialOfferController::class, 'store']);
    Route::apiResource('client-requests-client', ClientRequestClientController::class)->except('show');
    Route::apiResource('client-requests-admin', ClientRequestAdminController::class)->except('show');
});
