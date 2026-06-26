<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreActividadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $asignacion = $this->route('asignacion');
            $fecha = $this->input('fecha');

            if (! $fecha || ! $asignacion) {
                return;
            }

            $fechaCarbon = Carbon::parse($fecha);
            $periodo = $asignacion->periodoAcademico;

            if ($periodo) {
                if ($fechaCarbon->lt($periodo->fecha_inicio) || $fechaCarbon->gt($periodo->fecha_fin)) {
                    $validator->errors()->add('fecha', 'La fecha debe estar dentro del periodo académico.');
                }
            }

            if ($asignacion->dia_semana && $fechaCarbon->dayOfWeekIso !== $asignacion->dia_semana) {
                $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes'];
                $diaEsperado = $dias[$asignacion->dia_semana] ?? 'día de clase';
                $validator->errors()->add('fecha', "La fecha debe ser un {$diaEsperado} (día de clase de este curso).");
            }
        });
    }
}
