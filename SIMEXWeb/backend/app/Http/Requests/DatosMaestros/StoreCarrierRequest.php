<?php

namespace App\Http\Requests\DatosMaestros;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarrierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:50',
            'city_id' => 'required|integer|exists:cities,id',
        ];
    }
}
