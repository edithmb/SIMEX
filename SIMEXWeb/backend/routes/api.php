<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatosMaestros;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});

Route::get('datos-maestros/{tabla}', [DatosMaestros::class, 'index']);
Route::post('datos-maestros/{tabla}', [DatosMaestros::class, 'store']);
Route::put('datos-maestros/{tabla}/{id}', [DatosMaestros::class, 'update']);
Route::delete('datos-maestros/{tabla}/{id}', [DatosMaestros::class, 'destroy']);
