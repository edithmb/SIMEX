<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * Middleware de integración con Inertia.js: fija la vista raíz y define
 * los props compartidos automáticamente con cada respuesta Inertia.
 *
 * El usuario autenticado y el estado de la barra lateral se inyectan aquí
 * para evitar tener que fetchlearlo desde cada página.
 */
class HandleInertiaRequests extends Middleware
{
    /**
     * Plantilla Blade raíz cargada en la primera visita.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Versión actual de los assets; Inertia la usa para invalidar la caché
     * cuando cambian. Delegamos en el comportamiento por defecto.
     *
     * @see https://inertiajs.com/asset-versioning
     *
     * @param  Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Props compartidos en todas las respuestas Inertia.
     *
     * Incluye nombre de la app, usuario autenticado y preferencia de
     * sidebar (abierto por defecto si la cookie no está presente).
     *
     * @see https://inertiajs.com/shared-data
     *
     * @param  Request $request
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
