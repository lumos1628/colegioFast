<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidenciaConductaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo' => 'required|in:falta_leve,falta_grave,merito',
            'descripcion' => 'required|string|max:1000',
            'fecha' => 'required|date',
        ];
    }
}
