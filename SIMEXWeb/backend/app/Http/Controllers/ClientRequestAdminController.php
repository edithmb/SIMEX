<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientRequestAdminController extends Controller
{
    
    public function index(): JsonResponse
    {
        $clientRequests = ClientRequest::with(['client', 'origin', 'destination', 'commercialOffers'])->get();

        return response()->json($clientRequests);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_id'       => 'required|integer|exists:clients,id',
            'origin_id'       => 'required|integer|exists:locations,id',
            'destination_id'  => 'required|integer|exists:locations,id',
            'volume_m3'       => 'required|numeric|min:0',
            'gross_weight_kg' => 'required|numeric|min:0',
            'comments'        => 'nullable|string',
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
