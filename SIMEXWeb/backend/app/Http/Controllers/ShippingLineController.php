<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreShippingLineRequest;
use App\Models\ShippingLine;
use Illuminate\Http\JsonResponse;

/**
 * CRUD sobre el dato maestro `ShippingLine` (navieras).
 */
class ShippingLineController extends Controller
{
    /**
     * Listado completo de navieras con su ciudad asociada.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(ShippingLine::with('city:id,name')->get());
    }

    /**
     * Crea una naviera.
     *
     * @param  StoreShippingLineRequest $request
     * @return JsonResponse HTTP 201.
     */
    public function store(StoreShippingLineRequest $request): JsonResponse
    {
        $shippingLine = ShippingLine::create($request->validated());
        $shippingLine->load('city:id,name');

        return response()->json($shippingLine, 201);
    }

    /**
     * Actualiza una naviera.
     *
     * @param  StoreShippingLineRequest $request
     * @param  ShippingLine             $shippingLine Route-model binding.
     * @return JsonResponse
     */
    public function update(StoreShippingLineRequest $request, ShippingLine $shippingLine): JsonResponse
    {
        $shippingLine->update($request->validated());
        $shippingLine->load('city:id,name');

        return response()->json($shippingLine);
    }

    /**
     * Elimina una naviera.
     *
     * @param  ShippingLine $shippingLine
     * @return JsonResponse HTTP 204.
     */
    public function destroy(ShippingLine $shippingLine): JsonResponse
    {
        $shippingLine->delete();

        return response()->json(null, 204);
    }
}
