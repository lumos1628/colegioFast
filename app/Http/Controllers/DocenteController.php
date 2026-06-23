<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\IncidenciaConducta;
use App\Models\Matricula;
use App\Models\Nota;

class DocenteController extends Controller
{
    public function dashboard()
    {
        $docente = auth()->user()->docente;

        if (! $docente) {
            return view('docente.dashboard', ['asignaciones' => collect()]);
        }

        $asignaciones = $docente->asignaciones()
            ->with(['curso', 'periodoAcademico'])
            ->whereHas('periodoAcademico', fn ($q) => $q->where('activo', true))
            ->get();

        return view('docente.dashboard', compact('asignaciones'));
    }

    public function showCurso(Asignacion $asignacion)
    {
        $docente = auth()->user()->docente;

        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        $asignacion->load([
            'curso',
            'matriculas.alumno',
            'periodoAcademico',
        ]);

        return view('docente.curso', compact('asignacion'));
    }

    public function showAlumno(Asignacion $asignacion, Alumno $alumno)
    {
        $docente = auth()->user()->docente;

        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        Matricula::where('asignacion_id', $asignacion->id)
            ->where('alumno_id', $alumno->id)
            ->firstOrFail();

        $notas = Nota::where('alumno_id', $alumno->id)
            ->whereHas('actividad', fn ($q) => $q->where('asignacion_id', $asignacion->id))
            ->with('actividad.competencia')
            ->get();

        $asistencias = Asistencia::where('alumno_id', $alumno->id)
            ->where('asignacion_id', $asignacion->id)
            ->orderBy('fecha', 'desc')
            ->get();

        $incidencias = IncidenciaConducta::where('alumno_id', $alumno->id)
            ->get();

        return view('docente.alumno', compact('asignacion', 'alumno', 'notas', 'asistencias', 'incidencias'));
    }
}
