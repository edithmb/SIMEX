<?php

namespace App\Http\Requests\DatosMaestros;

use Illuminate\Foundation\Http\FormRequest;

class StoreContainerTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_name' => 'required|string|max:50',
        ];
    }
}
