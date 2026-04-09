<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ComercialOfferController extends Controller
{
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
