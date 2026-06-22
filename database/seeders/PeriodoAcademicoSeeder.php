<?php

namespace Database\Seeders;

use App\Models\PeriodoAcademico;
use Illuminate\Database\Seeder;

class PeriodoAcademicoSeeder extends Seeder
{
    public function run(): void
    {
        PeriodoAcademico::create([
            'nombre' => 'II Bimestre 2025',
            'fecha_inicio' => '2025-07-01',
            'fecha_fin' => '2025-11-30',
            'anio_escolar' => 2025,
            'activo' => false,
        ]);

        PeriodoAcademico::create([
            'nombre' => 'I Bimestre 2026',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-07-31',
            'anio_escolar' => 2026,
            'activo' => true,
        ]);
    }
}
