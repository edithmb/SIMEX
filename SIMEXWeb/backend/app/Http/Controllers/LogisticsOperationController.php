<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogisticsOperation;

/**
 * Controlador de operaciones logísticas (fase de seguimiento tras aceptar una oferta).
 *
 * Ofrece dos listados con paginación ajustable vía `per_page`:
 *  - `index`: todas las operaciones (vista interna).
 *  - `getByClient`: operaciones de un `client_id` dado (vista de cliente).
 *
 * Ambos endpoints eager-loadean el grafo completo (oferta → incoterm, puertos,
 * contenedor, solicitud → origen/destino/ciudad) con select explícito para
 * minimizar payload y evitar N+1.
 */
class LogisticsOperationController extends Controller
{
    /**
     * Listado paginado de todas las operaciones logísticas (vista admin).
     *
     * Acepta `per_page` por query string (por defecto 10). Devuelve además
     * los documentos subidos a cada operación con su tipo de documento.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse Paginator con operaciones enriquecidas.
     */
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
                'logisticsOperationDocuments' => function($q) {
                    $q->select([
                        'id',
                        'logistics_operation_id',
                        'document_type_id',
                        'file_url',
                        'file_name',
                        'status',
                        'is_ad_hoc',
                        'custom_name',
                        'uploaded_at',
                    ])->with('documentType:id,code,name');
                },
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

    /**
     * Listado paginado de operaciones logísticas de un cliente concreto.
     *
     * Pensado para la pantalla de seguimiento del cliente: `$clientId` se
     * pasa por ruta y filtra rigurosamente las operaciones devueltas.
     *
     * @param  Request     $request
     * @param  int|string  $clientId Id del cliente cuyas operaciones se listan.
     * @return \Illuminate\Http\JsonResponse
     */
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
