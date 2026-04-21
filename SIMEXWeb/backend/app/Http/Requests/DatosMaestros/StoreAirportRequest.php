<?php

namespace App\Http\Requests\DatosMaestros;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para la creación de un aeropuerto en los datos maestros.
 *
 * La autorización de rol se delega en el middleware `admin` que protege la
 * ruta; este Request sólo valida el payload.
 */
class StoreAirportRequest extends FormRequest
{
    /**
     * Autoriza la petición. Siempre true: el control de acceso ocurre en el
     * middleware de la ruta (`admin`).
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación del payload.
     *
     * Todos los campos son obligatorios. `code` acepta hasta 5 caracteres
     * (p.ej. código IATA) y `city_id` debe existir en `cities`.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'code'    => 'required|string|max:5',
            'name'    => 'required|string|max:120',
            'city_id' => 'required|integer|exists:cities,id',
        ];
    }
}
