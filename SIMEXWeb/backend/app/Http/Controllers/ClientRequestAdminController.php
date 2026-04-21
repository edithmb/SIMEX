<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador de solicitudes de cotización para el rol administrador.
 *
 * Permite ver todas las solicitudes del sistema (sin restricción por
 * cliente) y darlas de alta en nombre de cualquier cliente.
 */
class ClientRequestAdminController extends Controller
{
    /**
     * Listado global de solicitudes con cliente, origen, destino y ofertas.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $clientRequests = ClientRequest::with(['client', 'origin', 'destination', 'commercialOffers'])->get();

        return response()->json($clientRequests);
    }

    /**
     * Crea una solicitud de cotización en nombre de un cliente.
     *
     * La solicitud se inserta con estado inicial `enviado` y `created_by`
     * igual al usuario autenticado. Si `comments` no viene en el payload
     * se persiste una cadena vacía para cumplir el NOT NULL de la BD.
     *
     * @param  Request $request Payload validado inline.
     * @return JsonResponse HTTP 201 con la solicitud creada.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_id'       => 'required|integer|exists:clients,id',
            'origin_id'       => 'required|integer|exists:locations,id',
            'destination_id'  => 'required|integer|exists:locations,id',
            'volume_m3'       => 'required|numeric|min:0',
            'gross_weight_kg' => 'required|numeric|min:0',
            'comments'        => 'nullable|string',
            'responsability'  => 'required|in:BUYER,SELLER',
        ]);

        $clientRequest = ClientRequest::create([
            ...$validated,
            'comments'   => $validated['comments'] ?? '',
            'created_by' => auth()->id(),
            'estado'     => 'enviado',
        ]);

        return response()->json($clientRequest, 201);
    }
}
