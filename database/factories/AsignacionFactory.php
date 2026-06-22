<?php

namespace Database\Factories;

use App\Models\Asignacion;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\PeriodoAcademico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asignacion>
 */
class AsignacionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'docente_id' => Docente::factory(),
            'curso_id' => Curso::factory(),
            'periodo_academico_id' => PeriodoAcademico::factory(),
        ];
    }
}
