<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador de solicitudes de cotización para el rol cliente.
 *
 * A diferencia del controlador de admin, restringe los resultados a las
 * solicitudes creadas por el propio usuario y fija el `client_id` a partir
 * del usuario autenticado (no se admite del payload).
 */
class ClientRequestClientController extends Controller
{
    /**
     * Listado de solicitudes creadas por el usuario autenticado.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $clientRequests = ClientRequest::with(['client', 'origin', 'destination', 'commercialOffers'])
            ->where('created_by', auth()->id())
            ->get();

        return response()->json($clientRequests);
    }

    /**
     * Crea una solicitud de cotización asociada al cliente del usuario actual.
     *
     * `client_id` y `created_by` se derivan del usuario autenticado para
     * impedir que un cliente cree solicitudes en nombre de otro. Estado
     * inicial `enviado`.
     *
     * @param  Request $request
     * @return JsonResponse HTTP 201 con la solicitud creada.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
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
            'client_id'  => auth()->user()->client_id,
            'created_by' => auth()->id(),
            'estado'     => 'enviado',
        ]);

        return response()->json($clientRequest, 201);
    }
}
