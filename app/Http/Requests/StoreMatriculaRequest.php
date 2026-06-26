<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatriculaRequest extends FormRequest
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
            'alumno_id' => 'required|exists:alumnos,id',
            'grado' => 'required|integer|min:1|max:6',
            'seccion' => 'required|string|max:1',
        ];
    }
}
