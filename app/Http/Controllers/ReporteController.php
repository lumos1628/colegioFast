<?php

namespace App\Http\Controllers;

use App\Exports\LibretaNotasExport;
use App\Exports\ReporteCursoExport;
use App\Models\Alumno;
use App\Models\Asignacion;

class ReporteController extends Controller
{
    public function libreta(Alumno $alumno)
    {
        $user = auth()->user();
        $esAlumno = $user->alumno && $user->alumno->id === $alumno->id;

        $soloVisibles = $esAlumno;

        return (new LibretaNotasExport($alumno, $soloVisibles))->download();
    }

    public function reporteCurso(Asignacion $asignacion)
    {
        $docente = auth()->user()->docente;

        if ($docente) {
            abort_if($asignacion->docente_id !== $docente->id, 403);
        }

        return (new ReporteCursoExport($asignacion))->download();
    }
}
