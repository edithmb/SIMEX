<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

/**
 * Action de Fortify que restablece la contraseña tras un "olvidé mi contraseña".
 *
 * Usa `forceFill` para poder asignar `password` aunque no esté en `$fillable`.
 */
class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Valida la nueva contraseña y la persiste en el usuario.
     *
     * @param  User                   $user  Usuario al que se restablece la contraseña.
     * @param  array<string, string>  $input Payload con `password` y `password_confirmation`.
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException Si no cumple las reglas de contraseña.
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $user->forceFill([
            'password' => $input['password'],
        ])->save();
    }
}
