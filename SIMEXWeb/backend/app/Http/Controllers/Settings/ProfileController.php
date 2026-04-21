<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileDeleteRequest;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador Inertia para la pantalla de perfil del usuario autenticado.
 *
 * Permite editar datos personales, actualizar el email (invalidando la
 * verificación previa) y eliminar la cuenta previa confirmación por contraseña.
 */
class ProfileController extends Controller
{
    /**
     * Renderiza la página de edición de perfil.
     *
     * Pasa al frontend si la aplicación requiere verificación de email
     * (`mustVerifyEmail`) y el mensaje de estado de la sesión si existe.
     *
     * @param  Request $request
     * @return Response
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Actualiza la información de perfil del usuario autenticado.
     *
     * Si el email ha cambiado, se resetea `email_verified_at` para forzar
     * una nueva verificación antes de otorgar privilegios sensibles.
     *
     * @param  ProfileUpdateRequest $request
     * @return RedirectResponse     Redirige a la misma pantalla de edición.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('profile.edit');
    }

    /**
     * Elimina la cuenta del usuario autenticado.
     *
     * Cierra sesión primero, aplica el soft-delete sobre el usuario y luego
     * invalida la sesión y regenera el token CSRF para impedir reutilización.
     *
     * @param  ProfileDeleteRequest $request Exige confirmar la contraseña actual.
     * @return RedirectResponse     Redirige a `/`.
     */
    public function destroy(ProfileDeleteRequest $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
