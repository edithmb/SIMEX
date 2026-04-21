<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\TwoFactorAuthenticationRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

/**
 * Controlador Inertia para la pantalla de autenticación en dos factores (2FA).
 *
 * Implementa `HasMiddleware` para añadir dinámicamente el middleware
 * `password.confirm` sobre `show` cuando la opción `confirmPassword` de
 * Fortify está habilitada (exige reintroducir la contraseña antes de
 * acceder a la página sensible).
 */
class TwoFactorAuthenticationController extends Controller implements HasMiddleware
{
    /**
     * Middlewares dinámicos del controlador.
     *
     * Sólo añade `password.confirm` sobre `show` cuando Fortify tiene
     * habilitada la opción `confirmPassword` del feature 2FA.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            ? [new Middleware('password.confirm', only: ['show'])]
            : [];
    }

    /**
     * Renderiza la página de configuración 2FA.
     *
     * Antes de renderizar valida el estado 2FA de la sesión mediante
     * `ensureStateIsValid()` (provisto por `InteractsWithTwoFactorState`).
     * Pasa al frontend si el usuario ya tiene 2FA activo y si la app exige
     * confirmación adicional al configurar.
     *
     * @param  TwoFactorAuthenticationRequest $request
     * @return Response
     */
    public function show(TwoFactorAuthenticationRequest $request): Response
    {
        $request->ensureStateIsValid();

        return Inertia::render('settings/TwoFactor', [
            'twoFactorEnabled' => $request->user()->hasEnabledTwoFactorAuthentication(),
            'requiresConfirmation' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
        ]);
    }
}
