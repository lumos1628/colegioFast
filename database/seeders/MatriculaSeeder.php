<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Matricula;
use Illuminate\Database\Seeder;

class MatriculaSeeder extends Seeder
{
    public function run(): void
    {
        $asignaciones = Asignacion::all();
        $alumnos = Alumno::all();

        foreach ($asignaciones as $asignacion) {
            $cantidadAlumnos = rand(15, 30);
            $alumnosAsignados = $alumnos->random(min($cantidadAlumnos, $alumnos->count()));

            foreach ($alumnosAsignados as $alumno) {
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
