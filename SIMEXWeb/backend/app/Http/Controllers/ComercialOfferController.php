<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\CommercialOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComercialOfferController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_request_id'   => 'required|integer|exists:client_requests,id',
            'incoterm_id'         => 'required|integer|exists:incoterms,id',
            'origin_port_id'      => 'required|integer|exists:ports,id',
            'destination_port_id' => 'required|integer|exists:ports,id',
            'container_type_id'   => 'required|integer|exists:container_types,id',
            'price'               => 'required|numeric|min:0',
            'valid_until'         => 'required|date',
            'reference'           => 'nullable|string|max:255',
            'comments'            => 'nullable|string',
        ]);

        $offer = CommercialOffer::create([
            ...$validated,
            'client_id'  => ClientRequest::find($validated['client_request_id'])->client_id,
            'status'     => 'draft',
            'created_by' => auth()->id(),
        ]);

        return response()->json($offer, 201);
    }

    public function index()
    {
        $offers = CommercialOffer::select([
                'id', 
                'reference', 
                'client_request_id',
                'client_id',
                'incoterm_id',
                'container_type_id',
                'valid_until',
                'price', 
                'status',
                'rejection_reason',
                'comments',
                'created_at'
            ])
            ->with([
                'clientRequest' => function($query) {
                    $query->select([
                        'id', 
                        'volume_m3', 
                        'gross_weight_kg', 
                        'origin_id',
                        'destination_id',
                        'estado'
                    ]);
                },

                'clientRequest.origin:id,name',      
                'clientRequest.destination:id,name',
                
                'client:id,company_name',
                'incoterm:id,incoterm_type_id',
                'incoterm.incotermType:id,code,name',
                'containerType:id,type_name'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($offers);
    }
    public function getByClient($clientId)
    {
        $offers = CommercialOffer::select([
                'id', 
                'reference', 
                'client_request_id',
                'client_id',
                'incoterm_id',
                'container_type_id',
                'valid_until',
                'price', 
                'status',
                'rejection_reason',
                'comments',
                'created_at'
            ])
            ->where('client_id', $clientId) 
            ->with([
                'clientRequest' => function($query) {
                    $query->select([
                        'id', 
                        'volume_m3', 
                        'gross_weight_kg', 
                        'origin_id',
                        'destination_id',
                        'estado'
                    ]);
                },
                'clientRequest.origin:id,name',      
                'clientRequest.destination:id,name',
                'client:id,company_name',

                'incoterm:id,incoterm_type_id',
                'incoterm.incotermType:id,code,name',
                'containerType:id,type_name'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($offers);
    }
}
