<?php

namespace Database\Factories;

use App\Enums\PagoEstado;
use App\Models\Alumno;
use App\Models\Pago;
use App\Models\PeriodoAcademico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pago>
 */
class PagoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'alumno_id' => Alumno::factory(),
            'periodo_academico_id' => PeriodoAcademico::factory(),
            'concepto' => $this->faker->randomElement(['Pensión mensual', 'Matrícula', 'Material educativo', 'Excursión', 'Seguro escolar']),
            'monto' => $this->faker->randomFloat(2, 50, 500),
            'fecha_vencimiento' => $this->faker->date(),
            'estado' => $this->faker->randomElement(PagoEstado::cases()),
        ];
    }

    public function pagado(): static
    {
        return $this->state(fn () => ['estado' => PagoEstado::Pagado]);
    }

    public function pendiente(): static
    {
        return $this->state(fn () => ['estado' => PagoEstado::Pendiente]);
    }

    public function vencido(): static
    {
        return $this->state(fn () => ['estado' => PagoEstado::Vencido]);
    }
}
