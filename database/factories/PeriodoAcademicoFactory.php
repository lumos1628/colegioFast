<?php

namespace Database\Factories;

use App\Models\PeriodoAcademico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PeriodoAcademico>
 */
class PeriodoAcademicoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->randomElement(['I Bimestre', 'II Bimestre', 'III Bimestre', 'IV Bimestre']),
            'fecha_inicio' => $this->faker->date(),
            'fecha_fin' => $this->faker->date(),
            'anio_escolar' => $this->faker->year(),
            'activo' => false,
        ];
    }

    public function activo(): static
    {
        return $this->state(fn () => ['activo' => true]);
    }

    public function cerrado(): static
    {
        return $this->state(fn () => ['activo' => false]);
    }
}
