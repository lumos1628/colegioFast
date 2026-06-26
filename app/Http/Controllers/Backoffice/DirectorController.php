<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\IncidenciaConducta;
use App\Models\Matricula;
use App\Models\NotaBimestral;
use App\Models\PeriodoAcademico;

class DirectorController extends Controller
{
    public function dashboard()
    {
        $periodoActivo = PeriodoAcademico::where('activo', true)->first();

        $totalAlumnos = Alumno::count();
        $totalDocentes = Docente::count();
        $totalCursos = Curso::count();
        $totalMatriculas = $periodoActivo
            ? Matricula::whereHas('asignacion.periodoAcademico', fn ($q) => $q->where('activo', true))->count()
            : 0;

        $alumnosPorGrado = Alumno::selectRaw('grado, COUNT(*) as total')
            ->groupBy('grado')
            ->orderBy('grado')
            ->get();

        $incidenciasRecientes = IncidenciaConducta::with('alumno')
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get();

        $promedioGeneral = NotaBimestral::avg('promedio_numerico');

        $cursosConMasIncidencias = IncidenciaConducta::selectRaw('actividades.asignacion_id, COUNT(*) as total')
            ->join('notas', 'notas.alumno_id', '=', 'incidencias_conducta.alumno_id')
            ->join('actividades', 'actividades.id', '=', 'notas.actividad_id')
            ->groupBy('actividades.asignacion_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('backoffice.director.dashboard', compact(
            'totalAlumnos',
            'totalDocentes',
            'totalCursos',
            'totalMatriculas',
            'alumnosPorGrado',
            'incidenciasRecientes',
            'promedioGeneral',
            'periodoActivo'
        ));
    }
}
