<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de autorización por rol para rutas protegidas con JWT (guard `api`).
 *
 * Se usa como `role:admin,operador` en las rutas: resuelve el usuario
 * autenticado y comprueba que su `role->name` esté en la lista de roles
 * permitidos; de lo contrario responde 403 con un JSON neutro (sin filtrar
 * información del usuario).
 */
class EnsureUserHasRole
{
    /**
     * Ejecuta la comprobación de rol.
     *
     * @param  Request                 $request
     * @param  Closure(Request): Response $next
     * @param  string                  ...$roles Lista variádica de roles admitidos.
     * @return Response                JSON 403 si no autorizado; en caso contrario
     *                                 la respuesta del siguiente middleware/controlador.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth('api')->user();
        if (!$user || !in_array($user->role->name, $roles)) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        return $next($request);
    }
}
