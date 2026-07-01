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
        $periodos = PeriodoAcademico::all();
        $cursos = Curso::all();
        $docentes = Docente::all();

        $docenteData = [];
        foreach ($docentes as $docente) {
            $grados = $this->parseGrados($docente->telefono);
            $docenteData[] = [
                'id' => $docente->id,
                'especialidad' => $docente->especialidad,
                'grados' => $grados,
            ];
        }

        $horarios = [
            1 => ['08:00', '09:00', '10:00', '11:00', '12:00'],
            2 => ['08:00', '09:00', '10:00', '11:00', '12:00'],
            3 => ['08:00', '09:00', '10:00', '11:00', '12:00'],
            4 => ['08:00', '09:00', '10:00', '11:00', '12:00'],
            5 => ['08:00', '09:00', '10:00', '11:00', '12:00'],
        ];

        $duracion = [
            '08:00' => '09:00',
            '09:00' => '10:00',
            '10:00' => '11:00',
            '11:00' => '12:00',
            '12:00' => '13:00',
        ];

        $cursoDocenteMap = [];
        foreach ($cursos as $curso) {
            foreach ($docenteData as $dd) {
                if ($dd['especialidad'] === $curso->area_curricular && in_array($curso->grado, $dd['grados'])) {
                    $cursoDocenteMap[$curso->id] = $dd['id'];
                    break;
                }
            }
        }

        $teacherSlots = [];
        $sectionSlots = [];
        $sectionDayCount = [];

        foreach ($periodos as $periodo) {
            $teacherSlots = [];
            $sectionSlots = [];
            $sectionDayCount = [];

            $cursosParaPeriodo = $cursos->sortBy(function ($curso) {
                return $curso->grado * 10 + ord($curso->seccion);
            });

            foreach ($cursosParaPeriodo as $curso) {
                $docenteId = $cursoDocenteMap[$curso->id] ?? null;
                if (! $docenteId) {
                    continue;
                }

                $seccionKey = $curso->grado.'-'.$curso->seccion;
                $assigned = false;

                $diasOrden = $this->ordenDias($seccionKey);

                foreach ($diasOrden as $day) {
                    if ($assigned) {
                        break;
                    }
                    $dayCount = $sectionDayCount[$seccionKey][$day] ?? 0;
                    if ($dayCount >= 2) {
                        continue;
                    }

                    foreach ($horarios[$day] as $slotStart) {
                        $slotKey = $day.'-'.$slotStart;

                        $teacherBusy = isset($teacherSlots[$docenteId][$slotKey]);
                        $sectionBusy = isset($sectionSlots[$seccionKey][$slotKey]);

                        if (! $teacherBusy && ! $sectionBusy) {
                            Asignacion::create([
                                'docente_id' => $docenteId,
                                'curso_id' => $curso->id,
                                'periodo_academico_id' => $periodo->id,
                                'dia_semana' => $day,
                                'hora_inicio' => $slotStart,
                                'hora_fin' => $duracion[$slotStart],
                            ]);

                            $teacherSlots[$docenteId][$slotKey] = true;
                            $sectionSlots[$seccionKey][$slotKey] = true;
                            $sectionDayCount[$seccionKey][$day] = $dayCount + 1;
                            $assigned = true;
                            break;
                        }
                    }
                }
            }
        }
    }

    private function parseGrados(string $telefono): array
    {
        preg_match_all('/\d+/', $telefono, $matches);

        return array_map('intval', $matches[0] ?? []);
    }

    private function ordenDias(string $seccionKey): array
    {
        $dias = [1, 2, 3, 4, 5];
        $offset = crc32($seccionKey) % 5;

        return array_merge(array_slice($dias, $offset), array_slice($dias, 0, $offset));
    }
}
