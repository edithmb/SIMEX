<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreCityRequest;
use App\Models\City;
use Illuminate\Http\JsonResponse;

/**
 * CRUD sobre el dato maestro `City` (ciudades) para datos maestros.
 */
class CityController extends Controller
{
    /**
     * Listado de ciudades con su país asociado.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(City::with('country:id,name')->get());
    }

    /**
     * Crea una ciudad.
     *
     * @param  StoreCityRequest $request
     * @return JsonResponse HTTP 201.
     */
    public function store(StoreCityRequest $request): JsonResponse
    {
        $city = City::create($request->validated());
        $city->load('country:id,name');

        return response()->json($city, 201);
    }

    /**
     * Actualiza una ciudad existente.
     *
     * @param  StoreCityRequest $request
     * @param  City             $city Route-model binding.
     * @return JsonResponse
     */
    public function update(StoreCityRequest $request, City $city): JsonResponse
    {
        $city->update($request->validated());
        $city->load('country:id,name');

        return response()->json($city);
    }

    /**
     * Elimina una ciudad.
     *
     * @param  City $city
     * @return JsonResponse HTTP 204.
     */
    public function destroy(City $city): JsonResponse
    {
        $city->delete();

        return response()->json(null, 204);
    }
}
