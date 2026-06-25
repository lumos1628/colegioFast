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

        if (! $periodoActivo || $docentes->isEmpty()) {
            return;
        }

        // Horarios disponibles por día (máximo 4 cursos por día)
        $horariosPorDia = [
            1 => [
                ['inicio' => '08:00', 'fin' => '09:30'],
                ['inicio' => '10:00', 'fin' => '11:30'],
                ['inicio' => '12:00', 'fin' => '13:30'],
                ['inicio' => '14:00', 'fin' => '15:30'],
            ],
            2 => [
                ['inicio' => '08:00', 'fin' => '09:30'],
                ['inicio' => '10:00', 'fin' => '11:30'],
                ['inicio' => '12:00', 'fin' => '13:30'],
                ['inicio' => '14:00', 'fin' => '15:30'],
            ],
            3 => [
                ['inicio' => '08:00', 'fin' => '09:30'],
                ['inicio' => '10:00', 'fin' => '11:30'],
                ['inicio' => '12:00', 'fin' => '13:30'],
                ['inicio' => '14:00', 'fin' => '15:30'],
            ],
            4 => [
                ['inicio' => '08:00', 'fin' => '09:30'],
                ['inicio' => '10:00', 'fin' => '11:30'],
                ['inicio' => '12:00', 'fin' => '13:30'],
                ['inicio' => '14:00', 'fin' => '15:30'],
            ],
            5 => [
                ['inicio' => '08:00', 'fin' => '09:30'],
                ['inicio' => '10:00', 'fin' => '11:30'],
                ['inicio' => '12:00', 'fin' => '13:30'],
                ['inicio' => '14:00', 'fin' => '15:30'],
            ],
        ];

        // Contador de cursos asignados por día para cada docente
        $cursosPorDiaPorDocente = [];
        foreach ($docentes as $docente) {
            $cursosPorDiaPorDocente[$docente->id] = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        }

        // Distribuir cursos equitativamente
        $docenteIndex = 0;
        foreach ($cursos as $curso) {
            $docente = $docentes[$docenteIndex % $docentes->count()];

            // Encontrar el día con menos cursos para este docente
            $diaConMenosCursos = $this->getDiaConMenosCursos($cursosPorDiaPorDocente[$docente->id]);

            // Obtener el horario disponible para ese día
            $horarioIndex = $cursosPorDiaPorDocente[$docente->id][$diaConMenosCursos];
            $horario = $horariosPorDia[$diaConMenosCursos][$horarioIndex];

            Asignacion::create([
                'docente_id' => $docente->id,
                'curso_id' => $curso->id,
                'periodo_academico_id' => $periodoActivo->id,
                'dia_semana' => $diaConMenosCursos,
                'hora_inicio' => $horario['inicio'],
                'hora_fin' => $horario['fin'],
            ]);

            $cursosPorDiaPorDocente[$docente->id][$diaConMenosCursos]++;
            $docenteIndex++;
        }

        // Periodo cerrado (opcional, menos cursos)
        if ($periodoCerrado) {
            $cursosPeriodoCerrado = $cursos->take(12);
            foreach ($cursosPeriodoCerrado as $index => $curso) {
                $docente = $docentes[$index % $docentes->count()];
                $dia = ($index % 5) + 1;
                $horarioIndex = intdiv($index, 5) % 4;
                $horario = $horariosPorDia[$dia][$horarioIndex];

                Asignacion::create([
                    'docente_id' => $docente->id,
                    'curso_id' => $curso->id,
                    'periodo_academico_id' => $periodoCerrado->id,
                    'dia_semana' => $dia,
                    'hora_inicio' => $horario['inicio'],
                    'hora_fin' => $horario['fin'],
                ]);
            }
        }
    }

    private function getDiaConMenosCursos(array $cursosPorDia): int
    {
        $minCursos = min($cursosPorDia);
        $diasConMin = array_keys($cursosPorDia, $minCursos);

        // Si hay empate, elegir el día más bajo (Lunes primero)
        return min($diasConMin);
    }
}
