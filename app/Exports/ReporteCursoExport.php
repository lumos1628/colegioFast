<?php

namespace App\Exports;

use App\Models\Asignacion;
use App\Models\Competencia;
use App\Models\NotaBimestral;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteCursoExport
{
    public function __construct(private Asignacion $asignacion)
    {
        $this->asignacion->load(['curso', 'periodoAcademico', 'matriculas.alumno']);
    }

    public function download(): StreamedResponse
    {
        $fileName = sprintf(
            'reporte_%s_%s_%s.xlsx',
            $this->asignacion->curso->nombre,
            $this->asignacion->curso->grado.$this->asignacion->curso->seccion,
            now()->format('Y-m-d')
        );

        return response()->streamDownload(function () {
            $writer = new Writer;
            $writer->openToFile('php://output');

            $headerStyle = (new Style)
                ->setFontBold()
                ->setBackgroundColor(59, 130, 246);

            $writer->addRow(Row::fromValues([
                'REPORTE DE CALIFICACIONES',
            ], $headerStyle));

            $writer->addRow(Row::fromValues([
                "Curso: {$this->asignacion->curso->nombre}",
                "Grado: {$this->asignacion->curso->grado}° \"{$this->asignacion->curso->seccion}\"",
                "Periodo: {$this->asignacion->periodoAcademico->nombre}",
            ]));

            $writer->addRow(Row::fromValues([
                'Fecha de generación: '.now()->format('d/m/Y H:i'),
            ]));

            $writer->addRow(Row::fromValues([]));

            $competencias = $this->obtenerCompetencias();

            $headers = ['N°', 'Alumno'];
            foreach ($competencias as $comp) {
                $headers[] = Str::limit($comp->nombre, 20);
            }
            $headers[] = 'Promedio';
            $headers[] = 'Nivel';

            $writer->addRow(Row::fromValues($headers, $headerStyle));

            $alumnos = $this->asignacion->matriculas->pluck('alumno')->sortBy('apellido_paterno');

            $n = 1;
            foreach ($alumnos as $alumno) {
                $row = [$n, "{$alumno->nombres} {$alumno->apellido_paterno} {$alumno->apellido_materno}"];

                $promedios = [];
                foreach ($competencias as $comp) {
                    $promedio = NotaBimestral::where('alumno_id', $alumno->id)
                        ->where('asignacion_id', $this->asignacion->id)
                        ->where('competencia_id', $comp->id)
                        ->value('promedio_numerico');

                    $row[] = $promedio !== null ? number_format($promedio, 1) : '-';
                    if ($promedio !== null) {
                        $promedios[] = $promedio;
                    }
                }

                $promedioGeneral = count($promedios) > 0
                    ? array_sum($promedios) / count($promedios)
                    : null;

                $row[] = $promedioGeneral !== null ? number_format($promedioGeneral, 1) : '-';
                $row[] = $promedioGeneral !== null ? $this->nivelCalificacion($promedioGeneral) : '-';

                $writer->addRow(Row::fromValues($row));
                $n++;
            }

            $writer->close();
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function obtenerCompetencias(): Collection
    {
        $competenciaIds = $this->asignacion->actividades()
            ->distinct()
            ->pluck('competencia_id');

        return Competencia::whereIn('id', $competenciaIds)->get();
    }

    private function nivelCalificacion(float $promedio): string
    {
        return match (true) {
            $promedio >= 3.5 => 'AD - Logro Destacado',
            $promedio >= 2.5 => 'A - Logro Esperado',
            $promedio >= 1.5 => 'B - En Proceso',
            default => 'C - En Inicio',
        };
    }
}
