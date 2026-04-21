<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StoreAirportRequest;
use App\Models\Airport;
use Illuminate\Http\JsonResponse;

/**
 * CRUD sobre el dato maestro `Airport` (aeropuertos) para la sección de
 * datos maestros.
 *
 * Cada respuesta incluye la ciudad eager-loaded (sólo `id` y `name`) para
 * evitar N+1 y no exponer columnas innecesarias.
 */
class AirportController extends Controller
{
    /**
     * Listado completo de aeropuertos con su ciudad asociada.
     *
     * @return JsonResponse Array de aeropuertos con `city: { id, name }`.
     */
    public function index(): JsonResponse
    {
        return response()->json(Airport::with('city:id,name')->get());
    }

    /**
     * Crea un nuevo aeropuerto.
     *
     * @param  StoreAirportRequest $request Datos validados (`code`, `name`, `city_id`).
     * @return JsonResponse Aeropuerto creado con `city` eager-loaded, HTTP 201.
     */
    public function store(StoreAirportRequest $request): JsonResponse
    {
        $airport = Airport::create($request->validated());
        $airport->load('city:id,name');

        return response()->json($airport, 201);
    }

    /**
     * Actualiza un aeropuerto existente.
     *
     * @param  StoreAirportRequest $request Datos validados (mismo esquema que store).
     * @param  Airport             $airport Instancia resuelta por route-model binding.
     * @return JsonResponse                Aeropuerto actualizado.
     */
    public function update(StoreAirportRequest $request, Airport $airport): JsonResponse
    {
        $airport->update($request->validated());
        $airport->load('city:id,name');

        return response()->json($airport);
    }

    /**
     * Elimina un aeropuerto.
     *
     * @param  Airport $airport
     * @return JsonResponse HTTP 204 sin cuerpo.
     */
    public function destroy(Airport $airport): JsonResponse
    {
        $airport->delete();

        return response()->json(null, 204);
    }
}
