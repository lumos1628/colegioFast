<?php

namespace Database\Factories;

use App\Enums\Calificacion;
use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Nota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Nota>
 */
class NotaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'actividad_id' => Actividad::factory(),
            'alumno_id' => Alumno::factory(),
            'calificacion' => $this->faker->randomElement(Calificacion::cases()),
            'observacion' => $this->faker->optional()->sentence(),
            'visible_para_alumno' => true,
        ];
    }
}
