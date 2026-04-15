<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreContainerTypeRequest;
use App\Models\ContainerType;
use Illuminate\Http\JsonResponse;

class ContainerTypeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ContainerType::all());
    }

    public function store(StoreContainerTypeRequest $request): JsonResponse
    {
        $containerType = ContainerType::create($request->validated());

        return response()->json($containerType, 201);
    }

    public function update(StoreContainerTypeRequest $request, ContainerType $containerType): JsonResponse
    {
        $containerType->update($request->validated());

        return response()->json($containerType);
    }

    public function destroy(ContainerType $containerType): JsonResponse
    {
        $containerType->delete();

        return response()->json(null, 204);
    }
}
