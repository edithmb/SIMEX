<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\JsonResponse;

/**
 * Controlador de lectura de ubicaciones (`Location`).
 *
 * Usado para alimentar desplegables de origen/destino en los formularios
 * de solicitud de cotización.
 */
class LocationController extends Controller
{
    /**
     * Listado mínimo de ubicaciones (`id`, `name`) sin filtrar por cliente.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Location::select('id', 'name')->get());
    }
}
