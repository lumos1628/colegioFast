<?php

namespace Database\Seeders;

use App\Enums\PagoEstado;
use App\Models\Alumno;
use App\Models\Pago;
use App\Models\PeriodoAcademico;
use Illuminate\Database\Seeder;

class PagoSeeder extends Seeder
{
    public function run(): void
    {
        $alumnos = Alumno::all();
        $periodos = PeriodoAcademico::all();
        $conceptos = ['Pensión mensual', 'Matrícula', 'Material educativo', 'Excursión', 'Seguro escolar'];

        foreach ($alumnos->take(20) as $alumno) {
            foreach ($periodos as $periodo) {
                Pago::create([
                    'alumno_id' => $alumno->id,
                    'periodo_academico_id' => $periodo->id,
                    'concepto' => fake()->randomElement($conceptos),
                    'monto' => fake()->randomFloat(2, 50, 500),
                    'fecha_vencimiento' => fake()->dateTimeBetween('-1 month', '+2 months'),
                    'estado' => fake()->randomElement(PagoEstado::cases()),
                ]);
            }
        }
    }
}
