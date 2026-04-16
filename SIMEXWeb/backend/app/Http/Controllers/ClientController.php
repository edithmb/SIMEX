<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
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
