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
        $clientRequest = ClientRequest::create([
            'created_by' => auth()->id(),
            'volume_m3' => $request->input('volume_m3'),
            'gross_weight_kg' => $request->input('gross_weight_kg'),
            'comments' => $request->input('comments'),
            'origin_id' => $request->input('origin_id'),
            'destination_id' => $request->input('destination_id'),
            'estado' => 'enviado',
        ]);

        return response()->json($clientRequest, 201);
    }
}
