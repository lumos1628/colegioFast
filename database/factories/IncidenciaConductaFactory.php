<?php

namespace Database\Factories;

use App\Enums\IncidenciaTipo;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\IncidenciaConducta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IncidenciaConducta>
 */
class IncidenciaConductaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'alumno_id' => Alumno::factory(),
            'docente_id' => Docente::factory(),
            'tipo' => $this->faker->randomElement(IncidenciaTipo::cases()),
            'descripcion' => $this->faker->paragraph(),
            'fecha' => $this->faker->date(),
        ];
    }
}
