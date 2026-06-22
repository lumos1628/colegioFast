<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Matricula;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Matricula>
 */
class MatriculaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'alumno_id' => Alumno::factory(),
            'asignacion_id' => Asignacion::factory(),
            'fecha_matricula' => $this->faker->date(),
            'estado' => 'activo',
        ];
    }
}
