<?php

namespace App\Http\Requests\DatosMaestros;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la creación de un tipo de contenedor en datos maestros.
 *
 * La autorización de rol se delega en el middleware que protege la ruta.
 */
class StoreContainerTypeRequest extends FormRequest
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
            'type_name' => 'required|string|max:50',
        ];
    }
}
