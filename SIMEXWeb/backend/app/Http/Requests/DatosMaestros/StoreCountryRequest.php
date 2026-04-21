<?php

namespace App\Http\Requests\DatosMaestros;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la creación de un país en datos maestros.
 *
 * La autorización de rol se delega en el middleware que protege la ruta.
 */
class StoreCountryRequest extends FormRequest
{
    /**
     * Autoriza la petición. Siempre true (control de acceso en middleware).
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas del payload.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
        ];
    }
}
