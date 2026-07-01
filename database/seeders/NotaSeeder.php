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

        $pesos = [
            Calificacion::AD->value => 0.20,
            Calificacion::A->value => 0.50,
            Calificacion::B->value => 0.20,
            Calificacion::C->value => 0.10,
        ];

        foreach ($actividades as $actividad) {
            $matriculas = Matricula::where('asignacion_id', $actividad->asignacion_id)->get();

            foreach ($matriculas as $matricula) {
                if (mt_rand(1, 100) > 85) {
                    continue;
                }

                $calificacion = $this->calificacionPesada($pesos);

                Nota::create([
                    'actividad_id' => $actividad->id,
                    'alumno_id' => $matricula->alumno_id,
                    'calificacion' => $calificacion,
                    'observacion' => fake()->optional(0.15)->sentence(),
                    'visible_para_alumno' => true,
                ]);
            }
        }
    }

    private function calificacionPesada(array $pesos): Calificacion
    {
        $rand = mt_rand() / mt_getrandmax();
        $acumulado = 0;

        foreach (Calificacion::cases() as $cal) {
            $acumulado += $pesos[$cal->value];
            if ($rand <= $acumulado) {
                return $cal;
            }
        }

        return Calificacion::A;
    }
}
