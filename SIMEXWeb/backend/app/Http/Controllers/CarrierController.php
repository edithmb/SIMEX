<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreCarrierRequest;
use App\Models\Carrier;
use Illuminate\Http\JsonResponse;

class CarrierController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Carrier::with('city:id,name')->get());
    }

    public function store(StoreCarrierRequest $request): JsonResponse
    {
        $carrier = Carrier::create($request->validated());
        $carrier->load('city:id,name');

        return response()->json($carrier, 201);
    }

    public function update(StoreCarrierRequest $request, Carrier $carrier): JsonResponse
    {
        $carrier->update($request->validated());
        $carrier->load('city:id,name');

        return response()->json($carrier);
    }

    public function destroy(Carrier $carrier): JsonResponse
    {
        $carrier->delete();

        return response()->json(null, 204);
    }
}
