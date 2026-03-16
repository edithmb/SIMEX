<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreCityRequest;
use App\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(City::with('country:id,name')->get());
    }

    public function store(StoreCityRequest $request): JsonResponse
    {
        $city = City::create($request->validated());
        $city->load('country:id,name');

        return response()->json($city, 201);
    }

    public function update(StoreCityRequest $request, City $city): JsonResponse
    {
        $city->update($request->validated());
        $city->load('country:id,name');

        return response()->json($city);
    }

    public function destroy(City $city): JsonResponse
    {
        $city->delete();

        return response()->json(null, 204);
    }
}
