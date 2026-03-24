<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatosMaestros\StorePortRequest;
use App\Models\Port;
use Illuminate\Http\JsonResponse;

class PortController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Port::with('city:id,name')->get());
    }

    public function store(StorePortRequest $request): JsonResponse
    {
        $port = Port::create($request->validated());
        $port->load('city:id,name');

        return response()->json($port, 201);
    }

    public function update(StorePortRequest $request, Port $port): JsonResponse
    {
        $port->update($request->validated());
        $port->load('city:id,name');

        return response()->json($port);
    }

    public function destroy(Port $port): JsonResponse
    {
        $port->delete();

        return response()->json(null, 204);
    }
}
