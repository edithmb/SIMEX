<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreCountryRequest;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

/**
 * CRUD sobre el dato maestro `Country` (países).
 */
class CountryController extends Controller
{
    /**
     * Listado completo de países.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Country::all());
    }

    /**
     * Crea un país.
     *
     * @param  StoreCountryRequest $request
     * @return JsonResponse HTTP 201.
     */
    public function store(StoreCountryRequest $request): JsonResponse
    {
        $country = Country::create($request->validated());

        return response()->json($country, 201);
    }

    /**
     * Actualiza un país.
     *
     * @param  StoreCountryRequest $request
     * @param  Country             $country Route-model binding.
     * @return JsonResponse
     */
    public function update(StoreCountryRequest $request, Country $country): JsonResponse
    {
        $country->update($request->validated());

        return response()->json($country);
    }

    /**
     * Elimina un país.
     *
     * @param  Country $country
     * @return JsonResponse HTTP 204.
     */
    public function destroy(Country $country): JsonResponse
    {
        $country->delete();

        return response()->json(null, 204);
    }
}
