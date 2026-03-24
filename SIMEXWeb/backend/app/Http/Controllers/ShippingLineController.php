<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreShippingLineRequest;
use App\Models\ShippingLine;
use Illuminate\Http\JsonResponse;

class ShippingLineController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ShippingLine::with('city:id,name')->get());
    }

    public function store(StoreShippingLineRequest $request): JsonResponse
    {
        $shippingLine = ShippingLine::create($request->validated());
        $shippingLine->load('city:id,name');

        return response()->json($shippingLine, 201);
    }

    public function update(StoreShippingLineRequest $request, ShippingLine $shippingLine): JsonResponse
    {
        $shippingLine->update($request->validated());
        $shippingLine->load('city:id,name');

        return response()->json($shippingLine);
    }

    public function destroy(ShippingLine $shippingLine): JsonResponse
    {
        $shippingLine->delete();

        return response()->json(null, 204);
    }
}
