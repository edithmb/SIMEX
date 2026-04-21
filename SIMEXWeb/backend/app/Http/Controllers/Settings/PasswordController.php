<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PasswordUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador Inertia para la pantalla de cambio de contraseña del perfil.
 *
 * Renderiza el componente `settings/Password` y aplica la actualización
 * una vez validada por `PasswordUpdateRequest`.
 */
class PasswordController extends Controller
{
    /**
     * Renderiza la página de configuración de contraseña.
     *
     * @return Response
     */
    public function edit(): Response
    {
        return Inertia::render('settings/Password');
    }

    /**
     * Actualiza la contraseña del usuario autenticado.
     *
     * El cast `hashed` del modelo `User` se encarga de hashear antes de
     * persistir, por eso se asigna la nueva contraseña en claro.
     *
     * @param  PasswordUpdateRequest $request Validado con políticas de contraseña.
     * @return RedirectResponse      Redirige a la página anterior.
     */
    public function update(PasswordUpdateRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => $request->password,
        ]);

        return back();
    }
}
