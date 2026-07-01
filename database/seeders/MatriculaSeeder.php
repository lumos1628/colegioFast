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
        $periodos = PeriodoAcademico::all();
        $alumnos = Alumno::all();

        foreach ($periodos as $periodo) {
            $asignaciones = Asignacion::where('periodo_academico_id', $periodo->id)
                ->with('curso')
                ->get();

            foreach ($alumnos as $alumno) {
                $asignacionesDelGradoSeccion = $asignaciones->filter(function ($asignacion) use ($alumno) {
                    return $asignacion->curso->grado == $alumno->grado
                        && $asignacion->curso->seccion == $alumno->seccion;
                });

                foreach ($asignacionesDelGradoSeccion as $asignacion) {
                    Matricula::create([
                        'alumno_id' => $alumno->id,
                        'asignacion_id' => $asignacion->id,
                        'fecha_matricula' => $periodo->fecha_inicio,
                        'estado' => 'activo',
                    ]);
                }
            }
        }
    }
}
