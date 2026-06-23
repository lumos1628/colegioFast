<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreActividadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'competencia_id' => 'required|exists:competencias,id',
            'capacidad_id' => 'required|exists:capacidades,id',
        ];
    }
}
