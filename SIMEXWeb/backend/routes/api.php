<?php

use App\Http\Controllers\AirportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ContainerTypeController;
use App\Http\Controllers\CountryController;
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
});
