<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que propaga la preferencia de apariencia (tema) del usuario a
 * todas las vistas Blade mediante `View::share('appearance', …)`.
 *
 * Toma el valor de la cookie `appearance`; si no existe usa `'system'`
 * (seguir las preferencias del sistema operativo).
 */
class HandleAppearance
{
    /**
     * Propaga la preferencia de apariencia a la capa de vistas y delega en
     * el siguiente middleware/controlador.
     *
     * @param  Request                   $request
     * @param  Closure(Request): Response $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        View::share('appearance', $request->cookie('appearance') ?? 'system');

        return $next($request);
    }
}
