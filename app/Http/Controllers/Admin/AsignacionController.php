<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignacion;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\PeriodoAcademico;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Asignacion::query()
            ->with(['docente.user', 'curso', 'periodoAcademico'])
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio');

        if ($periodoId = $request->input('periodo')) {
            $query->where('periodo_academico_id', $periodoId);
        }

        $asignaciones = $query->paginate(20)->withQueryString();
        $periodos = PeriodoAcademico::orderBy('fecha_inicio', 'desc')->get();

        return view('administrativo.asignaciones.index', compact('asignaciones', 'periodos'));
    }

    public function create()
    {
        $docentes = Docente::with('user')->orderBy('user_id')->get();
        $cursos = Curso::orderBy('grado')->orderBy('seccion')->orderBy('nombre')->get();
        $periodos = PeriodoAcademico::orderBy('fecha_inicio', 'desc')->get();

        return view('administrativo.asignaciones.create', compact('docentes', 'cursos', 'periodos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'docente_id' => ['required', 'exists:docentes,id'],
            'curso_id' => ['required', 'exists:cursos,id'],
            'periodo_academico_id' => ['required', 'exists:periodo_academicos,id'],
            'dia_semana' => ['nullable', 'integer', 'min:1', 'max:5'],
            'hora_inicio' => ['nullable', 'date_format:H:i'],
            'hora_fin' => ['nullable', 'date_format:H:i', 'after:hora_inicio'],
        ]);

        Asignacion::create($data);

        return redirect()
            ->route('admin.asignaciones.index')
            ->with('success', 'Asignación creada correctamente');
    }

    public function edit(Asignacion $asignacion)
    {
        $docentes = Docente::with('user')->orderBy('user_id')->get();
        $cursos = Curso::orderBy('grado')->orderBy('seccion')->orderBy('nombre')->get();
        $periodos = PeriodoAcademico::orderBy('fecha_inicio', 'desc')->get();

        return view('administrativo.asignaciones.edit', compact('asignacion', 'docentes', 'cursos', 'periodos'));
    }

    public function update(Request $request, Asignacion $asignacion)
    {
        $data = $request->validate([
            'docente_id' => ['required', 'exists:docentes,id'],
            'curso_id' => ['required', 'exists:cursos,id'],
            'periodo_academico_id' => ['required', 'exists:periodo_academicos,id'],
            'dia_semana' => ['nullable', 'integer', 'min:1', 'max:5'],
            'hora_inicio' => ['nullable', 'date_format:H:i'],
            'hora_fin' => ['nullable', 'date_format:H:i', 'after:hora_inicio'],
        ]);

        $asignacion->update($data);

        return redirect()
            ->route('admin.asignaciones.index')
            ->with('success', 'Asignación actualizada correctamente');
    }

    public function destroy(Asignacion $asignacion)
    {
        if ($asignacion->matriculas()->exists()) {
            return redirect()
                ->route('admin.asignaciones.index')
                ->with('error', 'No se puede eliminar una asignación con matrículas');
        }

        $asignacion->delete();

        return redirect()
            ->route('admin.asignaciones.index')
            ->with('success', 'Asignación eliminada correctamente');
    }
}
