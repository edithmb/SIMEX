<?php

namespace App\Http\Requests\Settings;

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request de actualización de perfil (nombre, email, etc.) desde Settings.
 *
 * Las reglas concretas viven en el trait `ProfileValidationRules` y se
 * parametrizan con el id del usuario autenticado para ignorarlo en las
 * reglas `unique` (evita fallar la validación con su propio email).
 */
class ProfileUpdateRequest extends FormRequest
{
    use ProfileValidationRules;

    /**
     * Delega en `profileRules()` inyectando el id del usuario autenticado.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->profileRules($this->user()->id);
    }
}
