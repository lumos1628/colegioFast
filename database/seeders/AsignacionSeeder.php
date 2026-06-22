<?php

namespace Database\Seeders;

use App\Models\Asignacion;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\PeriodoAcademico;
use Illuminate\Database\Seeder;

class AsignacionSeeder extends Seeder
{
    public function run(): void
    {
        $periodoActivo = PeriodoAcademico::where('activo', true)->first();
        $periodoCerrado = PeriodoAcademico::where('activo', false)->first();

        $docentes = Docente::all();
        $cursos = Curso::all();

        if ($periodoActivo) {
            foreach ($cursos->take(5) as $curso) {
                Asignacion::create([
                    'docente_id' => $docentes->random()->id,
                    'curso_id' => $curso->id,
                    'periodo_academico_id' => $periodoActivo->id,
                ]);
            }
        }

        if ($periodoCerrado) {
            foreach ($cursos->take(3) as $curso) {
                Asignacion::create([
                    'docente_id' => $docentes->random()->id,
                    'curso_id' => $curso->id,
                    'periodo_academico_id' => $periodoCerrado->id,
                ]);
            }
        }
    }
}
