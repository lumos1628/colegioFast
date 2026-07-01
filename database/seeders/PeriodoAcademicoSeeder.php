<?php

namespace Database\Seeders;

use App\Models\PeriodoAcademico;
use Illuminate\Database\Seeder;

class PeriodoAcademicoSeeder extends Seeder
{
    public function run(): void
    {
        PeriodoAcademico::create([
            'nombre' => 'I Bimestre 2026',
            'fecha_inicio' => '2026-04-01',
            'fecha_fin' => '2026-08-31',
            'anio_escolar' => 2026,
            'activo' => true,
        ]);

        PeriodoAcademico::create([
            'nombre' => 'II Bimestre 2026',
            'fecha_inicio' => '2026-09-01',
            'fecha_fin' => '2026-11-30',
            'anio_escolar' => 2026,
            'activo' => false,
        ]);
    }
}
