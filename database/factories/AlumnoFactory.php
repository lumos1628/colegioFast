<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alumno>
 */
class AlumnoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->withRole(UserRole::Alumno),
            'nombres' => $this->faker->firstName(),
            'apellido_paterno' => $this->faker->lastName(),
            'apellido_materno' => $this->faker->lastName(),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-12 years', '-6 years'),
            'dni' => $this->faker->unique()->numerify('########'),
            'grado' => $this->faker->numberBetween(1, 6),
            'seccion' => $this->faker->randomElement(['A', 'B', 'C']),
        ];
    }
}
