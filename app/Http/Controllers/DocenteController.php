<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\IncidenciaConducta;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\NotaBimestral;

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
                'actividadesPendientes' => collect(),
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

        $actividadesPendientes = Actividad::whereHas('asignacion', function ($q) use ($docente) {
            $q->where('docente_id', $docente->id)
                ->whereHas('periodoAcademico', fn ($pq) => $pq->where('activo', true));
        })
            ->with(['asignacion.curso', 'competencia'])
            ->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha', 'asc')
            ->take(10)
            ->get();

        return view('docente.dashboard', array_merge($data, [
            'asignaciones' => $asignaciones,
            'actividadesPendientes' => $actividadesPendientes,
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

    public function actividadesPendientes()
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];

        if (! $docente) {
            return view('docente.actividades-pendientes', array_merge($data, [
                'actividadesPendientes' => collect(),
            ]));
        }

        $actividades = Actividad::whereHas('asignacion', function ($q) use ($docente) {
            $q->where('docente_id', $docente->id)
                ->whereHas('periodoAcademico', fn ($pq) => $pq->where('activo', true));
        })
            ->with(['asignacion.curso.matriculas', 'competencia', 'notas'])
            ->orderBy('fecha')
            ->get();

        $actividadesPendientes = $actividades->filter(function ($actividad) {
            if ($actividad->fecha->isToday() || $actividad->fecha->isFuture()) {
                return true;
            }

            $totalAlumnos = $actividad->asignacion->matriculas->count();
            $alumnosConNota = $actividad->notas->count();

            return $alumnosConNota < $totalAlumnos;
        });

        return view('docente.actividades-pendientes', array_merge($data, [
            'actividadesPendientes' => $actividadesPendientes,
        ]));
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

        $actividadesRecientes = $asignacion->actividades()
            ->with('competencia')
            ->orderBy('fecha', 'desc')
            ->take(4)
            ->get();

        return view('docente.curso', array_merge($data, compact('asignacion', 'actividadesRecientes')));
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

        $progresoBimestral = NotaBimestral::where('alumno_id', $alumno->id)
            ->where('asignacion_id', $asignacion->id)
            ->with('competencia')
            ->get();

        return view('docente.alumno', array_merge($data, compact('asignacion', 'alumno', 'notas', 'asistencias', 'incidencias', 'progresoBimestral')));
    }
}
