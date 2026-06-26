<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\NotaBimestral;

class AlumnoController extends Controller
{
    private function getAlumnoData(): array
    {
        $alumno = auth()->user()->alumno;

        if (! $alumno) {
            return ['alumno' => null, 'cursosPorDia' => collect()];
        }

        $matriculas = Matricula::where('alumno_id', $alumno->id)
            ->with(['asignacion.curso', 'asignacion.periodoAcademico'])
            ->whereHas('asignacion.periodoAcademico', fn ($q) => $q->where('activo', true))
            ->get();

        $cursos = $matriculas->pluck('asignacion')->sortBy(fn ($a) => $a->dia_semana ?? 0);

        $cursosPorDia = $cursos->groupBy('dia_semana');

        return [
            'alumno' => $alumno,
            'cursosPorDia' => $cursosPorDia,
        ];
    }

    public function dashboard()
    {
        $data = $this->getAlumnoData();
        $alumno = $data['alumno'];

        if (! $alumno) {
            return view('alumno.dashboard', array_merge($data, [
                'matriculas' => collect(),
                'progresoBimestral' => collect(),
            ]));
        }

        $matriculas = Matricula::where('alumno_id', $alumno->id)
            ->with(['asignacion.curso', 'asignacion.periodoAcademico'])
            ->whereHas('asignacion.periodoAcademico', fn ($q) => $q->where('activo', true))
            ->get();

        $progresoBimestral = NotaBimestral::where('alumno_id', $alumno->id)
            ->with(['asignacion.curso', 'competencia'])
            ->get();

        return view('alumno.dashboard', array_merge($data, [
            'matriculas' => $matriculas,
            'progresoBimestral' => $progresoBimestral,
        ]));
    }

    public function showCurso(Asignacion $asignacion)
    {
        $data = $this->getAlumnoData();
        $alumno = $data['alumno'];

        abort_if(! $alumno, 403);

        $matricula = Matricula::where('alumno_id', $alumno->id)
            ->where('asignacion_id', $asignacion->id)
            ->first();

        abort_if(! $matricula, 403);

        $asignacion->load(['curso', 'periodoAcademico']);

        $notas = Nota::where('alumno_id', $alumno->id)
            ->where('visible_para_alumno', true)
            ->whereHas('actividad', fn ($q) => $q->where('asignacion_id', $asignacion->id))
            ->with('actividad.competencia')
            ->get();

        $actividades = $asignacion->actividades()
            ->with(['competencia', 'notas' => fn ($q) => $q->where('alumno_id', $alumno->id)->where('visible_para_alumno', true)])
            ->orderBy('fecha')
            ->get();

        return view('alumno.curso', array_merge($data, compact('asignacion', 'notas', 'actividades')));
    }
}
