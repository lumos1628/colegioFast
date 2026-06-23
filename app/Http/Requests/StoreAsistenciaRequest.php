<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAsistenciaRequest extends FormRequest
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
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
            'asistencias.*.alumno_id' => 'required|exists:alumnos,id',
            'asistencias.*.estado' => 'required|in:presente,tardanza,ausente,justificado',
            'asistencias.*.observacion' => 'nullable|string',
        ];
    }
}
