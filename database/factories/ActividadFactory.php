<?php

namespace Database\Factories;

use App\Models\Actividad;
use App\Models\Asignacion;
use App\Models\Capacidad;
use App\Models\Competencia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Actividad>
 */
class ActividadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'asignacion_id' => Asignacion::factory(),
            'titulo' => $this->faker->sentence(3),
            'descripcion' => $this->faker->paragraph(),
            'fecha' => $this->faker->date(),
            'competencia_id' => Competencia::factory(),
            'capacidad_id' => Capacidad::factory(),
        ];
    }
}
