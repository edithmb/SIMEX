<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StorePortRequest;
use App\Models\Port;
use Illuminate\Http\JsonResponse;

/**
 * CRUD sobre el dato maestro `Port` (puertos marítimos).
 */
class PortController extends Controller
{
    /**
     * Listado completo de puertos con su ciudad asociada.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Port::with('city:id,name')->get());
    }

    /**
     * Crea un puerto.
     *
     * @param  StorePortRequest $request
     * @return JsonResponse HTTP 201.
     */
    public function store(StorePortRequest $request): JsonResponse
    {
        $port = Port::create($request->validated());
        $port->load('city:id,name');

        return response()->json($port, 201);
    }

    /**
     * Actualiza un puerto.
     *
     * @param  StorePortRequest $request
     * @param  Port             $port Route-model binding.
     * @return JsonResponse
     */
    public function update(StorePortRequest $request, Port $port): JsonResponse
    {
        $port->update($request->validated());
        $port->load('city:id,name');

        return response()->json($port);
    }

    /**
     * Elimina un puerto.
     *
     * @param  Port $port
     * @return JsonResponse HTTP 204.
     */
    public function destroy(Port $port): JsonResponse
    {
        $port->delete();

        return response()->json(null, 204);
    }
}
