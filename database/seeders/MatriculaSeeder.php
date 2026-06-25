<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Matricula;
use App\Models\PeriodoAcademico;
use Illuminate\Database\Seeder;

class MatriculaSeeder extends Seeder
{
    public function run(): void
    {
        $periodoActivo = PeriodoAcademico::where('activo', true)->first();

        if (! $periodoActivo) {
            return;
        }

        $alumnos = Alumno::all();
        $asignaciones = Asignacion::where('periodo_academico_id', $periodoActivo->id)
            ->with('curso')
            ->get();

        foreach ($alumnos as $alumno) {
            $asignacionesDelGrado = $asignaciones->filter(function ($asignacion) use ($alumno) {
                return $asignacion->curso->grado == $alumno->grado
                    && $asignacion->curso->seccion == $alumno->seccion;
            });

            foreach ($asignacionesDelGrado as $asignacion) {
                if (! Matricula::where('alumno_id', $alumno->id)
                    ->where('asignacion_id', $asignacion->id)
                    ->exists()) {
                    Matricula::create([
                        'alumno_id' => $alumno->id,
                        'asignacion_id' => $asignacion->id,
                        'fecha_matricula' => now()->startOfYear(),
                        'estado' => 'activo',
                    ]);
                }
            }
        }
    }
}
