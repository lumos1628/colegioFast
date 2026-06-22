<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Asignacion;
use App\Models\Capacidad;
use App\Models\Competencia;
use Illuminate\Database\Seeder;

class ActividadSeeder extends Seeder
{
    public function run(): void
    {
        $asignaciones = Asignacion::all();
        $competencias = Competencia::all();

        foreach ($asignaciones as $asignacion) {
            $cantidadActividades = rand(3, 5);

            for ($i = 0; $i < $cantidadActividades; $i++) {
                $competencia = $competencias->random();
                $capacidad = $competencia->capacidades->isNotEmpty()
                    ? $competencia->capacidades->random()
                    : Capacidad::factory()->create(['competencia_id' => $competencia->id]);

                Actividad::create([
                    'asignacion_id' => $asignacion->id,
                    'titulo' => fake()->sentence(3),
                    'descripcion' => fake()->paragraph(),
                    'fecha' => fake()->dateTimeBetween('-3 months', 'now'),
                    'competencia_id' => $competencia->id,
                    'capacidad_id' => $capacidad->id,
                ]);
            }
        }
    }
}
