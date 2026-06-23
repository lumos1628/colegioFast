<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreNotaRequest extends FormRequest
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
            'notas' => 'required|array',
            'notas.*.alumno_id' => 'required|exists:alumnos,id',
            'notas.*.calificacion' => 'required|in:AD,A,B,C',
            'notas.*.observacion' => 'nullable|string',
        ];
    }
}
