<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;

/**
 * Controlador de lectura de clientes para la pantalla de gestión interna.
 *
 * Devuelve un listado enriquecido con los usuarios activos de cada cliente
 * y su rol, usando select explícito para no exponer columnas sensibles
 * (p.ej. `password_hash`, auditoría).
 */
class ClientController extends Controller
{
    /**
     * Lista los clientes con sus usuarios activos y el rol de cada uno.
     *
     * Se excluyen usuarios inactivos (`is_active = false`) y se cargan
     * únicamente las columnas necesarias para la vista de clientes.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $clients = Client::select([
                'id',
                'company_name',
                'vat_number',
                'address',
                'country',
                'postal_code',
                'contact_name',
                'email',
                'phone',
            ])
            ->with([
                'users' => fn ($q) => $q
                    ->select(['id', 'client_id', 'role_id', 'first_name', 'last_name', 'email', 'phone_number', 'is_active'])
                    ->with('role:id,name')
                    ->where('is_active', true),
            ])
            ->get();

        return response()->json($clients);
    }
}
