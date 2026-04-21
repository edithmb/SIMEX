<?php

namespace App\Concerns;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Trait con las reglas de validación reutilizables para contraseñas.
 *
 * `Password::default()` se configura desde `AppServiceProvider::configureDefaults`
 * — en producción exige 12 caracteres, mayúsculas/minúsculas, números, símbolos
 * y comprobación de filtraciones; en dev queda sin restricciones extra.
 */
trait PasswordValidationRules
{
    /**
     * Reglas aplicables a una nueva contraseña: formato + confirmación (`password_confirmation`).
     *
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
    }

    /**
     * Reglas aplicables a la contraseña actual (para confirmar acciones sensibles).
     *
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function currentPasswordRules(): array
    {
        return ['required', 'string', 'current_password'];
    }
}
