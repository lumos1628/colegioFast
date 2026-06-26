<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodoAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodoAcademicoController extends Controller
{
    public function index()
    {
        $periodos = PeriodoAcademico::orderBy('fecha_inicio', 'desc')->get();

        return view('administrativo.periodos.index', compact('periodos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
            'anio_escolar' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        PeriodoAcademico::create($data);

        return redirect()
            ->route('admin.periodos.index')
            ->with('success', 'Periodo creado correctamente');
    }

    public function update(Request $request, PeriodoAcademico $periodo)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
            'anio_escolar' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        $periodo->update($data);

        return redirect()
            ->route('admin.periodos.index')
            ->with('success', 'Periodo actualizado correctamente');
    }

    public function activar(PeriodoAcademico $periodo)
    {
        DB::transaction(function () use ($periodo) {
            PeriodoAcademico::where('id', '!=', $periodo->id)->update(['activo' => false]);
            $periodo->update(['activo' => true]);
        });

        return redirect()
            ->route('admin.periodos.index')
            ->with('success', "Periodo \"{$periodo->nombre}\" activado correctamente");
    }

    public function destroy(PeriodoAcademico $periodo)
    {
        if ($periodo->activo) {
            return redirect()
                ->route('admin.periodos.index')
                ->with('error', 'No se puede eliminar un periodo activo');
        }

        $periodo->delete();

        return redirect()
            ->route('admin.periodos.index')
            ->with('success', 'Periodo eliminado correctamente');
    }
}
