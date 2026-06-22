<?php

namespace Database\Factories;

use App\Models\Capacidad;
use App\Models\Competencia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Capacidad>
 */
class CapacidadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'competencia_id' => Competencia::factory(),
            'nombre' => $this->faker->sentence(3),
        ];
    }
}
