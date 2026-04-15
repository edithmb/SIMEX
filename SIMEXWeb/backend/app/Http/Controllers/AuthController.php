<?php

namespace App\Http\Controllers;

use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
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

    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user()->load('role'));
    }

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
