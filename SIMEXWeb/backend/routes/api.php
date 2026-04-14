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
use App\Http\Controllers\LogisticsOperationController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ShippingLineController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Rutas compartidas para todos los usuarios autenticados
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/locations', [LocationController::class, 'index']);
    Route::get('/incoterms', [IncotermController::class, 'index']);
    Route::get('/clients', [ClientController::class, 'index']);

    // Comercial offers - compartido
    Route::get('/commercial-offers', [ComercialOfferController::class, 'index']);
    Route::get('/commercial-offers/mine', [ComercialOfferController::class, 'mine']);
    Route::post('/commercial-offers', [ComercialOfferController::class, 'store']);
    Route::put('/commercial-offers/{id}/approve', [ComercialOfferController::class, 'approve']);
    Route::put('/commercial-offers/{id}/reject', [ComercialOfferController::class, 'reject']);

    // Client requests - compartido
    Route::apiResource('client-requests-client', ClientRequestClientController::class)->except('show');
    Route::apiResource('client-requests-admin', ClientRequestAdminController::class)->except('show');

    Route::get('/logistics-operations', [LogisticsOperationController::class, 'index']);
    Route::get('/roles', fn () => response()->json(Role::select('id', 'name', 'description')->get()));

    // Datos maestros - GET para todos, POST/PUT/DELETE solo admin
    Route::get('/countries', [CountryController::class, 'index']);
    Route::get('/cities', [CityController::class, 'index']);
    Route::get('/ports', [PortController::class, 'index']);
    Route::get('/airports', [AirportController::class, 'index']);
    Route::get('/shipping-lines', [ShippingLineController::class, 'index']);
    Route::get('/carriers', [CarrierController::class, 'index']);
    Route::get('/container-types', [ContainerTypeController::class, 'index']);

    // POST/PUT/DELETE solo admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/countries', [CountryController::class, 'store']);
        Route::put('/countries/{country}', [CountryController::class, 'update']);
        Route::delete('/countries/{country}', [CountryController::class, 'destroy']);

        Route::post('/cities', [CityController::class, 'store']);
        Route::put('/cities/{city}', [CityController::class, 'update']);
        Route::delete('/cities/{city}', [CityController::class, 'destroy']);

        Route::post('/ports', [PortController::class, 'store']);
        Route::put('/ports/{port}', [PortController::class, 'update']);
        Route::delete('/ports/{port}', [PortController::class, 'destroy']);

        Route::post('/airports', [AirportController::class, 'store']);
        Route::put('/airports/{airport}', [AirportController::class, 'update']);
        Route::delete('/airports/{airport}', [AirportController::class, 'destroy']);

        Route::post('/shipping-lines', [ShippingLineController::class, 'store']);
        Route::put('/shipping-lines/{shipping_line}', [ShippingLineController::class, 'update']);
        Route::delete('/shipping-lines/{shipping_line}', [ShippingLineController::class, 'destroy']);

        Route::post('/carriers', [CarrierController::class, 'store']);
        Route::put('/carriers/{carrier}', [CarrierController::class, 'update']);
        Route::delete('/carriers/{carrier}', [CarrierController::class, 'destroy']);

        Route::post('/container-types', [ContainerTypeController::class, 'store']);
        Route::put('/container-types/{container_type}', [ContainerTypeController::class, 'update']);
        Route::delete('/container-types/{container_type}', [ContainerTypeController::class, 'destroy']);
    });
});
