<?php

namespace App\Http\Requests\Settings;

use App\Concerns\PasswordValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request de actualización de contraseña desde la sección Settings.
 *
 * Reutiliza las reglas del trait `PasswordValidationRules`: `current_password`
 * debe coincidir con la contraseña actual y `password` cumplir la política
 * de complejidad/longitud de la aplicación.
 */
class PasswordUpdateRequest extends FormRequest
{
    use PasswordValidationRules;

    /**
     * Reglas de validación: contraseña actual + nueva contraseña.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => $this->currentPasswordRules(),
            'password' => $this->passwordRules(),
        ];
    }
}
