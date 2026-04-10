<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientRequestClientController extends Controller
{
    
    public function index(): JsonResponse
    {
        $clientRequests = ClientRequest::with(['client', 'origin', 'destination', 'commercialOffers'])
            ->where('created_by', auth()->id())
            ->get();

        return response()->json($clientRequests);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'origin_id'       => 'required|integer|exists:locations,id',
            'destination_id'  => 'required|integer|exists:locations,id',
            'volume_m3'       => 'required|numeric|min:0',
            'gross_weight_kg' => 'required|numeric|min:0',
            'comments'        => 'nullable|string',
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
