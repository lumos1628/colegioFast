<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Padre;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Padre>
 */
class PadreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->withRole(UserRole::Padre),
            'nombres' => $this->faker->firstName(),
            'apellido_paterno' => $this->faker->lastName(),
            'apellido_materno' => $this->faker->lastName(),
            'dni' => $this->faker->unique()->numerify('########'),
            'telefono' => $this->faker->numerify('9########'),
            'direccion' => $this->faker->address(),
        ];
    }
}
