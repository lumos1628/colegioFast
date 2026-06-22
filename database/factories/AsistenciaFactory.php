<?php

namespace Database\Factories;

use App\Enums\AsistenciaEstado;
use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Asistencia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asistencia>
 */
class AsistenciaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'alumno_id' => Alumno::factory(),
            'asignacion_id' => Asignacion::factory(),
            'fecha' => $this->faker->date(),
            'estado' => $this->faker->randomElement(AsistenciaEstado::cases()),
            'observacion' => null,
        ];
    }

    public function presente(): static
    {
        return $this->state(fn () => ['estado' => AsistenciaEstado::Presente]);
    }

    public function ausente(): static
    {
        return $this->state(fn () => ['estado' => AsistenciaEstado::Ausente]);
    }

    public function tardanza(): static
    {
        return $this->state(fn () => ['estado' => AsistenciaEstado::Tardanza]);
    }

    public function justificado(): static
    {
        return $this->state(fn () => [
            'estado' => AsistenciaEstado::Justificado,
            'observacion' => $this->faker->sentence(),
        ]);
    }
}
