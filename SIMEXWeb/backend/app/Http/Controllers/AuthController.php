<?php

namespace App\Http\Controllers;

use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Controlador de autenticación: gestiona login, consulta del usuario actual y logout.
 *
 * Emite JWT mediante tymon/jwt-auth y registra sesiones en `login_sessions`
 * para poder invalidar tokens por sesión (claim `sid`).
 */
class AuthController extends Controller
{
    /**
     * Autentica al usuario y devuelve un JWT asociado a una nueva LoginSession.
     *
     * Valida credenciales contra usuarios activos (`User::active()`). Si son
     * correctas, crea una fila en `login_sessions` y firma el JWT añadiendo
     * el claim `sid` con el id de la sesión para poder invalidar esa sesión
     * específica en logout.
     *
     * @param  Request  $request  Debe traer `email` y `password`.
     * @return JsonResponse       `{ token, token_type, expires_in, user: { id, first_name, last_name, email, role: { id, name } } }`.
     *
     * @throws ValidationException Si las credenciales son incorrectas.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::active()->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->getAuthPassword())) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales incorrectas.'],
            ]);
        }

        $ttlMinutes = (int) config('jwt.ttl');

        $session = LoginSession::create([
            'user_id'          => $user->id,
            'ip_address'       => $request->ip(),
            'user_agent'       => $request->userAgent(),
            'device_type'      => $this->detectDeviceType($request->userAgent()),
            'logged_in_at'     => now(),
            'token_expires_at' => now()->addMinutes($ttlMinutes),
        ]);

        $token = JWTAuth::claims(['sid' => $session->id])->fromUser($user);

        $user->load('role');

        return response()->json([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttlMinutes * 60,
            'user'       => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'role'       => [
                    'id'   => $user->role->id,
                    'name' => $user->role->name,
                ],
            ],
        ]);
    }

    /**
     * Devuelve el usuario autenticado con su rol cargado.
     *
     * Requiere un JWT válido en la cabecera `Authorization` (guard `api`).
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user()->load('role'));
    }

    /**
     * Cierra la sesión actual: marca `logged_out_at` en la LoginSession del
     * claim `sid` e invalida el JWT.
     *
     * Cualquier error al actualizar la tabla de sesiones se ignora: el
     * tracking es best-effort pero la invalidación del token siempre se ejecuta.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $sid = auth('api')->payload()->get('sid');
            if ($sid) {
                LoginSession::where('id', $sid)
                    ->whereNull('logged_out_at')
                    ->update(['logged_out_at' => now()]);
            }
        } catch (\Throwable $e) {
            // ignoramos: aunque falle el tracking, el token debe invalidarse
        }

        auth('api')->logout();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    /**
     * Clasifica heurísticamente el user-agent en un tipo de dispositivo.
     *
     * Orden de detección: tablet (incluye iPad) → mobile → desktop.
     * Devuelve `'unknown'` si no se dispone de user-agent.
     *
     * @param  string|null $ua Cadena User-Agent del request.
     * @return string      `'tablet'|'mobile'|'desktop'|'unknown'`.
     */
    private function detectDeviceType(?string $ua): string
    {
        if (! $ua) {
            return 'unknown';
        }
        if (preg_match('/tablet|ipad/i', $ua)) {
            return 'tablet';
        }
        if (preg_match('/mobile|android|iphone/i', $ua)) {
            return 'mobile';
        }
        return 'desktop';
    }
}
