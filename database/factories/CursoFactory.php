<?php

namespace Database\Factories;

use App\Models\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Curso>
 */
class CursoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->randomElement(['Matemática', 'Comunicación', 'Personal Social', 'Ciencia y Tecnología', 'Arte y Cultura', 'Educación Física', 'Inglés']),
            'area_curricular' => $this->faker->randomElement(['Matemática', 'Comunicación', 'Personal Social', 'Ciencia y Tecnología', 'Arte y Cultura', 'Educación Física', 'Inglés']),
            'grado' => $this->faker->numberBetween(1, 6),
            'seccion' => $this->faker->randomElement(['A', 'B', 'C']),
        ];
    }
}
