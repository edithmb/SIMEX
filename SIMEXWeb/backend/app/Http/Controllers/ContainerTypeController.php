<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreContainerTypeRequest;
use App\Models\ContainerType;
use Illuminate\Http\JsonResponse;

/**
 * CRUD sobre el dato maestro `ContainerType` (tipos de contenedor).
 */
class ContainerTypeController extends Controller
{
    /**
     * Listado completo de tipos de contenedor.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(ContainerType::all());
    }

    /**
     * Crea un tipo de contenedor.
     *
     * @param  StoreContainerTypeRequest $request
     * @return JsonResponse HTTP 201.
     */
    public function store(StoreContainerTypeRequest $request): JsonResponse
    {
        $containerType = ContainerType::create($request->validated());

        return response()->json($containerType, 201);
    }

    /**
     * Actualiza un tipo de contenedor.
     *
     * @param  StoreContainerTypeRequest $request
     * @param  ContainerType             $containerType Route-model binding.
     * @return JsonResponse
     */
    public function update(StoreContainerTypeRequest $request, ContainerType $containerType): JsonResponse
    {
        $containerType->update($request->validated());

        return response()->json($containerType);
    }

    /**
     * Elimina un tipo de contenedor.
     *
     * @param  ContainerType $containerType
     * @return JsonResponse HTTP 204.
     */
    public function destroy(ContainerType $containerType): JsonResponse
    {
        $containerType->delete();

        return response()->json(null, 204);
    }
}
