<?php

namespace App\Exports;

use App\Models\Alumno;
use App\Models\Nota;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LibretaNotasExport
{
    private bool $soloVisibles;

    public function __construct(private Alumno $alumno, bool $soloVisibles = false)
    {
        $this->soloVisibles = $soloVisibles;
    }

    public function download(): StreamedResponse
    {
        $fileName = sprintf(
            'libreta_%s_%s_%s.xlsx',
            $this->alumno->apellido_paterno,
            $this->alumno->nombres,
            now()->format('Y-m-d')
        );

        return response()->streamDownload(function () {
            $writer = new Writer;
            $writer->openToFile('php://output');

            $headerStyle = (new Style)
                ->setFontBold()
                ->setBackgroundColor(59, 130, 246);

            $writer->addRow(Row::fromValues([
                'LIBRETA DE NOTAS',
            ], $headerStyle));

            $writer->addRow(Row::fromValues([
                "Alumno: {$this->alumno->nombres} {$this->alumno->apellido_paterno} {$this->alumno->apellido_materno}",
            ]));

            $writer->addRow(Row::fromValues([
                "Grado: {$this->alumno->grado}° \"{$this->alumno->seccion}\"",
                "DNI: {$this->alumno->dni}",
                'Fecha: '.now()->format('d/m/Y'),
            ]));

            $writer->addRow(Row::fromValues([]));

            $writer->addRow(Row::fromValues([
                'Curso',
                'Competencia',
                'Actividad',
                'Fecha',
                'Calificación',
                'Nivel',
            ], $headerStyle));

            $query = Nota::where('alumno_id', $this->alumno->id)
                ->with(['actividad.asignacion.curso', 'actividad.competencia']);

            if ($this->soloVisibles) {
                $query->where('visible_para_alumno', true);
            }

            $notas = $query->get()->sortBy(function ($nota) {
                return [
                    $nota->actividad->asignacion->curso->nombre ?? '',
                    $nota->actividad->competencia->nombre ?? '',
                    $nota->actividad->fecha,
                ];
            });

            foreach ($notas as $nota) {
                $writer->addRow(Row::fromValues([
                    $nota->actividad->asignacion->curso->nombre ?? '-',
                    $nota->actividad->competencia->nombre ?? '-',
                    $nota->actividad->titulo ?? '-',
                    $nota->actividad->fecha?->format('d/m/Y') ?? '-',
                    $nota->calificacion->value ?? '-',
                    $nota->calificacion->label() ?? '-',
                ]));
            }

            $writer->close();
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
