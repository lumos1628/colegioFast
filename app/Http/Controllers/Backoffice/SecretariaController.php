<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\PeriodoAcademico;

class SecretariaController extends Controller
{
    public function dashboard()
    {
        $periodoActivo = PeriodoAcademico::where('activo', true)->first();

        $totalAlumnos = Alumno::count();

        $matriculasRecientes = collect();
        if ($periodoActivo) {
            $matriculasRecientes = Matricula::whereHas('asignacion.periodoAcademico', fn ($q) => $q->where('activo', true))
                ->with(['alumno', 'asignacion.curso'])
                ->orderBy('fecha_matricula', 'desc')
                ->take(10)
                ->get();
        }

        $pagosPendientes = Pago::whereIn('estado', ['pendiente', 'vencido'])
            ->with(['alumno', 'periodoAcademico'])
            ->orderBy('fecha_vencimiento')
            ->take(10)
            ->get();

        $alumnosSinMatricula = Alumno::whereDoesntHave('matriculas', function ($q) use ($periodoActivo) {
            if ($periodoActivo) {
                $q->whereHas('asignacion.periodoAcademico', fn ($pq) => $pq->where('activo', true));
            }
        })->take(10)->get();

        return view('backoffice.secretaria.dashboard', compact(
            'totalAlumnos',
            'matriculasRecientes',
            'pagosPendientes',
            'alumnosSinMatricula',
            'periodoActivo'
        ));
    }
}
