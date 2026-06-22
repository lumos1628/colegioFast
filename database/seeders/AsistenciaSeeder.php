<?php

namespace Database\Seeders;

use App\Enums\AsistenciaEstado;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Matricula;
use Illuminate\Database\Seeder;

class AsistenciaSeeder extends Seeder
{
    public function run(): void
    {
        $asignaciones = Asignacion::all();

        foreach ($asignaciones as $asignacion) {
            $matriculas = Matricula::where('asignacion_id', $asignacion->id)->get();
            $fechas = collect();

            for ($i = 0; $i < 10; $i++) {
                $fechas->push(now()->subDays(rand(1, 60))->format('Y-m-d'));
            }

            foreach ($matriculas as $matricula) {
                foreach ($fechas as $fecha) {
                    if (! Asistencia::where('alumno_id', $matricula->alumno_id)
                        ->where('asignacion_id', $asignacion->id)
                        ->where('fecha', $fecha)
                        ->exists()) {
                        $estado = fake()->randomElement([
                            AsistenciaEstado::Presente,
                            AsistenciaEstado::Presente,
                            AsistenciaEstado::Presente,
                            AsistenciaEstado::Tardanza,
                            AsistenciaEstado::Ausente,
                            AsistenciaEstado::Justificado,
                        ]);

                        Asistencia::create([
                            'alumno_id' => $matricula->alumno_id,
                            'asignacion_id' => $asignacion->id,
                            'fecha' => $fecha,
                            'estado' => $estado,
                            'observacion' => $estado === AsistenciaEstado::Justificado
                                ? fake()->sentence()
                                : null,
                        ]);
                    }
                }
            }
        }
    }
}
