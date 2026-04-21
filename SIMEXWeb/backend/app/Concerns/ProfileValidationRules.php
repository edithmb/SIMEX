<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;

/**
 * Trait con las reglas de validación reutilizables para datos de perfil.
 *
 * Permite pasar `$userId` al componer la regla `unique` para ignorar al
 * propio usuario al editar su perfil (evita fallar `email`).
 */
trait ProfileValidationRules
{
    /**
     * Reglas completas de perfil: nombre + email (único).
     *
     * @param  int|null $userId Id del usuario actual a ignorar en `unique`.
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($userId),
        ];
    }

    /**
     * Reglas de validación del nombre (`name`).
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Reglas de validación del email. Al editar perfil (`$userId` informado)
     * se ignora el propio usuario en la regla `unique`.
     *
     * @param  int|null $userId
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }
}
