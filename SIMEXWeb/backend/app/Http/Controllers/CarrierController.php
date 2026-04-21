<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreCarrierRequest;
use App\Models\Carrier;
use Illuminate\Http\JsonResponse;

/**
 * CRUD sobre el dato maestro `Carrier` (transportistas) para datos maestros.
 */
class CarrierController extends Controller
{
    /**
     * Listado completo de transportistas con su ciudad asociada.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Carrier::with('city:id,name')->get());
    }

    /**
     * Crea un transportista.
     *
     * @param  StoreCarrierRequest $request
     * @return JsonResponse HTTP 201.
     */
    public function store(StoreCarrierRequest $request): JsonResponse
    {
        $carrier = Carrier::create($request->validated());
        $carrier->load('city:id,name');

        return response()->json($carrier, 201);
    }

    /**
     * Actualiza un transportista existente.
     *
     * @param  StoreCarrierRequest $request
     * @param  Carrier             $carrier Route-model binding.
     * @return JsonResponse
     */
    public function update(StoreCarrierRequest $request, Carrier $carrier): JsonResponse
    {
        $carrier->update($request->validated());
        $carrier->load('city:id,name');

        return response()->json($carrier);
    }

    /**
     * Elimina un transportista.
     *
     * @param  Carrier $carrier
     * @return JsonResponse HTTP 204.
     */
    public function destroy(Carrier $carrier): JsonResponse
    {
        $carrier->delete();

        return response()->json(null, 204);
    }
}
