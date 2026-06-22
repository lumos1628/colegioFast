<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Docente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Docente>
 */
class DocenteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->withRole(UserRole::Docente),
            'especialidad' => $this->faker->randomElement(['Matemática', 'Comunicación', 'Ciencias', 'Historia', 'Inglés', 'Educación Física']),
            'telefono' => $this->faker->numerify('9########'),
        ];
    }
}
