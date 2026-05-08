<?php

namespace App\Http\Controllers;

use App\Models\Incoterm;
use Illuminate\Http\JsonResponse;

/**
 * Controlador de lectura de Incoterms.
 *
 * Se usa en formularios (p.ej. al crear una oferta comercial) para poblar
 * el desplegable de Incoterms disponibles.
 */
class IncotermController extends Controller
{
    /**
     * Listado de Incoterms ordenados por `order_num` con su tipo asociado.
     *
     * Sólo se exponen `id` e `incoterm_type_id` del Incoterm más el
     * `IncotermType` en forma reducida (`id`, `code`, `name`).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $incoterms = Incoterm::with('incotermType:id,code,name')
            ->orderBy('order_num')
            ->get(['id', 'incoterm_type_id']); 

        return response()->json($incoterms);
    }
}
