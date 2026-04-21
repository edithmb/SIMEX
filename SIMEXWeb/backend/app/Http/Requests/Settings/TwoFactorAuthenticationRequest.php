<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Features;
use Laravel\Fortify\InteractsWithTwoFactorState;

/**
 * Request para activar o interactuar con el 2FA (Fortify).
 *
 * Sólo autoriza la petición si la feature de Two Factor está habilitada
 * globalmente en la config de Fortify; en caso contrario Laravel responde
 * con 403 automáticamente.
 */
class TwoFactorAuthenticationRequest extends FormRequest
{
    use InteractsWithTwoFactorState;

    /**
     * Autoriza sólo si la feature `twoFactorAuthentication` está habilitada.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Features::enabled(Features::twoFactorAuthentication());
    }

    /**
     * No requiere campos en el cuerpo: las claves 2FA se gestionan en sesión.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
