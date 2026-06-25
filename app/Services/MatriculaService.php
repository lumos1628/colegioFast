<?php

namespace App\Services;

use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Matricula;
use App\Models\PeriodoAcademico;
use Illuminate\Support\Facades\DB;

class MatriculaService
{
    public function matricularAlumnoEnGrado(Alumno $alumno, int $grado, string $seccion, PeriodoAcademico $periodo): int
    {
        $asignaciones = Asignacion::where('periodo_academico_id', $periodo->id)
            ->whereHas('curso', function ($query) use ($grado, $seccion) {
                $query->where('grado', $grado)
                    ->where('seccion', $seccion);
            })
            ->get();

        $matriculasCreadas = 0;

        DB::transaction(function () use ($alumno, $asignaciones, &$matriculasCreadas) {
            foreach ($asignaciones as $asignacion) {
                $existe = Matricula::where('alumno_id', $alumno->id)
                    ->where('asignacion_id', $asignacion->id)
                    ->exists();

                if (! $existe) {
                    Matricula::create([
                        'alumno_id' => $alumno->id,
                        'asignacion_id' => $asignacion->id,
                        'fecha_matricula' => now(),
                        'estado' => 'activo',
                    ]);
                    $matriculasCreadas++;
                }
            }
        });

        return $matriculasCreadas;
    }

    public function matricularAlumnoActual(Alumno $alumno): int
    {
        $periodoActivo = PeriodoAcademico::where('activo', true)->first();

        if (! $periodoActivo) {
            return 0;
        }

        return $this->matricularAlumnoEnGrado(
            $alumno,
            $alumno->grado,
            $alumno->seccion,
            $periodoActivo
        );
    }
}
