<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

/**
 * Action de Fortify que valida y crea un nuevo usuario durante el registro.
 *
 * Combina las reglas de perfil y de contraseña (traits) y delega la
 * persistencia en el modelo `User` (cuyo cast `hashed` hashea la contraseña
 * antes de almacenar).
 */
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Valida el payload de registro y crea el usuario.
     *
     * @param  array<string, string> $input Payload con `name`, `email`,
     *                                      `password` y `password_confirmation`.
     * @return User                  Usuario recién creado.
     *
     * @throws \Illuminate\Validation\ValidationException Si el payload es inválido.
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);
    }
}
