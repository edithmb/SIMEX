<?php

use App\Http\Controllers\DatosMaestros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('datos-maestros/{tabla}', [DatosMaestros::class, 'index']);
Route::post('datos-maestros/{tabla}', [DatosMaestros::class, 'store']);
Route::put('datos-maestros/{tabla}/{id}', [DatosMaestros::class, 'update']);
Route::delete('datos-maestros/{tabla}/{id}', [DatosMaestros::class, 'destroy']);
