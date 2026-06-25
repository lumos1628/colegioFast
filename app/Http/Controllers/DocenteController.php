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
    private function getDocenteData(): array
    {
        $docente = auth()->user()->docente;

        if (! $docente) {
            return ['docente' => null, 'cursosPorDia' => collect()];
        }

        $cursos = $docente->asignaciones()
            ->with(['curso', 'periodoAcademico'])
            ->whereHas('periodoAcademico', fn ($q) => $q->where('activo', true))
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        $cursosPorDia = $cursos->groupBy('dia_semana');

        return [
            'docente' => $docente,
            'cursosPorDia' => $cursosPorDia,
        ];
    }

    public function dashboard()
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];

        if (! $docente) {
            return view('docente.dashboard', array_merge($data, [
                'asignaciones' => collect(),
                'fecha' => now(),
            ]));
        }

        $diaSemana = now()->dayOfWeekIso;
        $asignaciones = $docente->asignaciones()
            ->with(['curso', 'periodoAcademico'])
            ->whereHas('periodoAcademico', fn ($q) => $q->where('activo', true))
            ->where('dia_semana', $diaSemana)
            ->orderBy('hora_inicio')
            ->get();

        return view('docente.dashboard', array_merge($data, [
            'asignaciones' => $asignaciones,
            'fecha' => now(),
        ]));
    }

    public function horario()
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];

        if (! $docente) {
            return view('docente.horario', array_merge($data, [
                'asignacionesPorDia' => collect(),
            ]));
        }

        $asignaciones = $docente->asignaciones()
            ->with(['curso', 'periodoAcademico'])
            ->whereHas('periodoAcademico', fn ($q) => $q->where('activo', true))
            ->whereNotNull('dia_semana')
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        $asignacionesPorDia = $asignaciones->groupBy('dia_semana');

        return view('docente.horario', array_merge($data, compact('asignacionesPorDia')));
    }

    public function showCurso(Asignacion $asignacion)
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];

        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        $asignacion->load([
            'curso',
            'matriculas.alumno',
            'periodoAcademico',
        ]);

        return view('docente.curso', array_merge($data, compact('asignacion')));
    }

    public function showAlumno(Asignacion $asignacion, Alumno $alumno)
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];

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

        return view('docente.alumno', array_merge($data, compact('asignacion', 'alumno', 'notas', 'asistencias', 'incidencias')));
    }
}
