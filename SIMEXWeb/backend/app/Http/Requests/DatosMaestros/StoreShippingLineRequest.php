<?php

namespace App\Http\Requests\DatosMaestros;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la creación de una naviera (shipping line) en datos maestros.
 *
 * La autorización de rol se delega en el middleware que protege la ruta.
 */
class StoreShippingLineRequest extends FormRequest
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
     * Reglas del payload. `city_id` debe existir en `cities`.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:50',
            'city_id' => 'required|integer|exists:cities,id',
        ];
    }
}
