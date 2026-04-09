<?php

namespace App\Http\Requests\DatosMaestros;

use Illuminate\Foundation\Http\FormRequest;

class StoreAirportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'    => 'required|string|max:5',
            'name'    => 'required|string|max:120',
            'city_id' => 'required|integer|exists:cities,id',
        ];
    }
}
