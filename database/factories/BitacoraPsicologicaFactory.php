<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Alumno;
use App\Models\BitacoraPsicologica;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BitacoraPsicologica>
 */
class BitacoraPsicologicaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'alumno_id' => Alumno::factory(),
            'psicologo_id' => User::factory()->withRole(UserRole::Psicologo),
            'fecha' => $this->faker->date(),
            'observaciones' => $this->faker->paragraphs(3, true),
        ];
    }
}
