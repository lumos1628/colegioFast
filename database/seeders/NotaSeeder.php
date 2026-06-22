<?php

namespace Database\Seeders;

use App\Enums\Calificacion;
use App\Models\Actividad;
use App\Models\Matricula;
use App\Models\Nota;
use Illuminate\Database\Seeder;

class NotaSeeder extends Seeder
{
    public function run(): void
    {
        $actividades = Actividad::with('asignacion')->get();

        foreach ($actividades as $actividad) {
            $matriculas = Matricula::where('asignacion_id', $actividad->asignacion_id)->get();

            foreach ($matriculas as $matricula) {
                if (! Nota::where('actividad_id', $actividad->id)
                    ->where('alumno_id', $matricula->alumno_id)
                    ->exists()) {
                    $calificaciones = Calificacion::cases();
                    $pesos = [
                        Calificacion::AD->value => 0.2,
                        Calificacion::A->value => 0.5,
                        Calificacion::B->value => 0.2,
                        Calificacion::C->value => 0.1,
                    ];

                    $rand = mt_rand() / mt_getrandmax();
                    $acumulado = 0;
                    $calificacionSeleccionada = Calificacion::A;

                    foreach ($calificaciones as $cal) {
                        $acumulado += $pesos[$cal->value];
                        if ($rand <= $acumulado) {
                            $calificacionSeleccionada = $cal;
                            break;
                        }
                    }

                    Nota::create([
                        'actividad_id' => $actividad->id,
                        'alumno_id' => $matricula->alumno_id,
                        'calificacion' => $calificacionSeleccionada,
                        'observacion' => fake()->optional(0.3)->sentence(),
                        'visible_para_alumno' => true,
                    ]);
                }
            }
        }
    }
}
