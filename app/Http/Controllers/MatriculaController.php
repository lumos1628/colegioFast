<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatriculaRequest;
use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\PeriodoAcademico;
use App\Services\MatriculaService;

class MatriculaController extends Controller
{
    public function index()
    {
        $periodoActivo = PeriodoAcademico::where('activo', true)->first();

        if (! $periodoActivo) {
            return view('administrativo.matriculas.index', [
                'matriculas' => collect(),
                'periodoActivo' => null,
            ]);
        }

        $matriculas = Matricula::whereHas('asignacion', function ($query) use ($periodoActivo) {
            $query->where('periodo_academico_id', $periodoActivo->id);
        })
            ->with(['alumno', 'asignacion.curso', 'asignacion.docente'])
            ->orderBy('fecha_matricula', 'desc')
            ->get();

        return view('administrativo.matriculas.index', compact('matriculas', 'periodoActivo'));
    }

    public function create()
    {
        $periodoActivo = PeriodoAcademico::where('activo', true)->firstOrFail();

        $alumnosMatriculados = Matricula::whereHas('asignacion', function ($query) use ($periodoActivo) {
            $query->where('periodo_academico_id', $periodoActivo->id);
        })->pluck('alumno_id')->toArray();

        $alumnos = Alumno::whereNotIn('id', $alumnosMatriculados)
            ->orderBy('apellido_paterno')
            ->orderBy('nombres')
            ->get();

        return view('administrativo.matriculas.create', compact('alumnos', 'periodoActivo'));
    }

    public function store(StoreMatriculaRequest $request, MatriculaService $matriculaService)
    {
        $periodoActivo = PeriodoAcademico::where('activo', true)->firstOrFail();
        $alumno = Alumno::findOrFail($request->alumno_id);

        $matriculasCreadas = $matriculaService->matricularAlumnoEnGrado(
            $alumno,
            $request->grado,
            $request->seccion,
            $periodoActivo
        );

        if ($matriculasCreadas > 0) {
            return redirect()
                ->route('admin.matriculas.index')
                ->with('success', "Alumno matriculado en {$matriculasCreadas} cursos");
        }

        return redirect()
            ->route('admin.matriculas.index')
            ->with('warning', 'No se encontraron cursos para ese grado/sección');
    }

    public function destroy(Matricula $matricula)
    {
        $matricula->delete();

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula eliminada correctamente');
    }
}
