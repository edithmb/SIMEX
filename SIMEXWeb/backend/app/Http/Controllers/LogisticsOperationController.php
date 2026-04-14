<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogisticsOperation;

class LogisticsOperationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);

        $select = [
            'id',
            'reference',
            'commercial_offer_id',
            'client_id',
            'status',
            'etd',
            'eta',
            'atd',
            'ata',
            'created_at',
            'updated_at',
            'completed_at',
        ];


        $query = LogisticsOperation::select($select)
            ->with([
                'client:id,company_name',

                'commercialOffer' => function($q) {
                    $q->select([
                        'id',
                        'reference',
                        'client_request_id',
                        'client_id',
                        'incoterm_id',
                        'origin_port_id',
                        'destination_port_id',
                        'container_type_id',
                        'price',
                        'valid_until',
                        'status',
                        'created_at'
                    ])
                    ->with([
                        'incoterm:id,incoterm_type_id',
                        'incoterm.incotermType:id,code,name',
                        'containerType:id,type_name',
                        'originPort:id,name',
                        'destinationPort:id,name',
                        'clientRequest' => function($qr) {
                            $qr->select([
                                'id',
                                'client_id',
                                'volume_m3',
                                'gross_weight_kg',
                                'origin_id',
                                'destination_id',
                                'responsability',
                            ])
                            ->with([
                                'origin:id,name,city_id',
                                'origin.city:id,name',
                                'destination:id,name,city_id',
                                'destination.city:id,name'
                            ]);
                        }
                    ]);
                }
            ])
            ->orderBy('created_at', 'desc');

        $ops = $query->paginate($perPage);

        return response()->json($ops);
    }

    public function getByClient(Request $request, $clientId)
    {
        $perPage = (int) $request->query('per_page', 10);

        $select = [
            'id',
            'reference',
            'commercial_offer_id',
            'client_id',
            'status',
            'etd',
            'eta',
            'atd',
            'ata',
            'odoo_id',
            'created_at',
            'updated_at',
            'completed_at',
        ];

        $query = LogisticsOperation::select($select)
            ->where('client_id', $clientId)
            ->with([
                'client:id,company_name',
                'commercialOffer' => function($q) {
                    $q->select([
                        'id',
                        'reference',
                        'client_request_id',
                        'client_id',
                        'incoterm_id',
                        'origin_port_id',
                        'destination_port_id',
                        'container_type_id',
                        'price',
                        'valid_until',
                        'status',
                        'odoo_id',
                        'created_at'
                    ])
                    ->with([
                        'incoterm:id,incoterm_type_id',
                        'incoterm.incotermType:id,code,name',
                        'containerType:id,type_name',
                        'originPort:id,name',
                        'destinationPort:id,name',
                        'clientRequest' => function($qr) {
                            $qr->select([
                                'id',
                                'client_id',
                                'volume_m3',
                                'gross_weight_kg',
                                'origin_id',
                                'destination_id',
                                'responsability',
                            ])
                            ->with([
                                'origin:id,name,city_id',
                                'origin.city:id,name',
                                'destination:id,name,city_id',
                                'destination.city:id,name'
                            ]);
                        }
                    ]);
                }
            ])
            ->orderBy('created_at', 'desc');

        $ops = $query->paginate($perPage);

        return response()->json($ops);
    }
}
