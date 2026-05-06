<?php

namespace App\Http\Controllers;

use App\Models\Incoterm;
use Illuminate\Http\JsonResponse;

class IncotermController extends Controller
{
    public function index(): JsonResponse
    {
        $incoterms = Incoterm::with('incotermType:id,code,name')
            ->orderBy('order_num')
            ->get(['id', 'incoterm_type_id']); 

        return response()->json($incoterms);
    }
}
