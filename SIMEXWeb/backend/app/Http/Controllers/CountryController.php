<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreCountryRequest;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Country::all());
    }

    public function store(StoreCountryRequest $request): JsonResponse
    {
        $country = Country::create($request->validated());

        return response()->json($country, 201);
    }

    public function update(StoreCountryRequest $request, Country $country): JsonResponse
    {
        $country->update($request->validated());

        return response()->json($country);
    }

    public function destroy(Country $country): JsonResponse
    {
        $country->delete();

        return response()->json(null, 204);
    }
}
