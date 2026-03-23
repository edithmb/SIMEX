<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreAirportRequest;
use App\Models\Airport;
use Illuminate\Http\JsonResponse;

class AirportController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Airport::with('city:id,name')->get());
    }

    public function store(StoreAirportRequest $request): JsonResponse
    {
        $airport = Airport::create($request->validated());
        $airport->load('city:id,name');

        return response()->json($airport, 201);
    }

    public function update(StoreAirportRequest $request, Airport $airport): JsonResponse
    {
        $airport->update($request->validated());
        $airport->load('city:id,name');

        return response()->json($airport);
    }

    public function destroy(Airport $airport): JsonResponse
    {
        $airport->delete();

        return response()->json(null, 204);
    }
}
