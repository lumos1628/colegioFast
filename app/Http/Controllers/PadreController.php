<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\NotaBimestral;
use App\Models\Notificacion;
use App\Models\Pago;

class PadreController extends Controller
{
    private function getPadreData(): array
    {
        $padre = auth()->user()->padre;

        if (! $padre) {
            return ['padre' => null, 'hijos' => collect()];
        }

        $hijos = $padre->alumnos()
            ->with(['matriculas.asignacion.curso', 'matriculas.asignacion.periodoAcademico'])
            ->get();

        return [
            'padre' => $padre,
            'hijos' => $hijos,
        ];
    }

    public function dashboard()
    {
        $data = $this->getPadreData();
        $padre = $data['padre'];

        if (! $padre) {
            return view('padre.dashboard', array_merge($data, [
                'progresoPorHijo' => collect(),
                'notificacionesRecientes' => collect(),
                'actividadesPendientes' => collect(),
            ]));
        }

        $hijos = $padre->alumnos()->get();

        $progresoPorHijo = collect();
        foreach ($hijos as $hijo) {
            $progreso = NotaBimestral::where('alumno_id', $hijo->id)
                ->with(['asignacion.curso', 'competencia'])
                ->get();

            $progresoPorHijo->put($hijo->id, [
                'hijo' => $hijo,
                'progreso' => $progreso,
            ]);
        }

        $notificacionesRecientes = Notificacion::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $hijoIds = $hijos->pluck('id');
        $asignacionIds = Matricula::whereIn('alumno_id', $hijoIds)
            ->whereHas('asignacion.periodoAcademico', fn ($q) => $q->where('activo', true))
            ->pluck('asignacion_id')
            ->unique();

        $actividadesPendientes = Actividad::whereIn('asignacion_id', $asignacionIds)
            ->where('fecha', '>=', now()->toDateString())
            ->with(['asignacion.curso', 'competencia', 'notas' => fn ($q) => $q->whereIn('alumno_id', $hijoIds)])
            ->orderBy('fecha', 'asc')
            ->get()
            ->filter(function ($actividad) use ($hijoIds) {
                $alumnosConNota = $actividad->notas->pluck('alumno_id')->unique();

                return $alumnosConNota->count() < $hijoIds->count();
            })
            ->take(10)
            ->values();

        return view('padre.dashboard', array_merge($data, [
            'progresoPorHijo' => $progresoPorHijo,
            'notificacionesRecientes' => $notificacionesRecientes,
            'actividadesPendientes' => $actividadesPendientes,
        ]));
    }

    public function showHijo(Alumno $alumno)
    {
        $data = $this->getPadreData();
        $padre = $data['padre'];

        abort_if(! $padre, 403);

        $esTutor = $padre->alumnos()->where('alumnos.id', $alumno->id)->exists();
        abort_if(! $esTutor, 403);

        $progresoBimestral = NotaBimestral::where('alumno_id', $alumno->id)
            ->with(['asignacion.curso', 'competencia'])
            ->get();

        $notas = Nota::where('alumno_id', $alumno->id)
            ->with(['actividad.asignacion.curso', 'actividad.competencia'])
            ->get();

        $notasPorCurso = $notas->groupBy(fn ($nota) => $nota->actividad->asignacion_id);

        $asistencias = $alumno->asistencias()
            ->with('asignacion.curso')
            ->orderBy('fecha', 'desc')
            ->get();

        $incidencias = $alumno->incidenciasConducta()->get();

        return view('padre.hijo', array_merge($data, compact(
            'alumno',
            'progresoBimestral',
            'notasPorCurso',
            'asistencias',
            'incidencias'
        )));
    }

    public function notificaciones()
    {
        $data = $this->getPadreData();

        $filtro = request('filtro', 'todas');

        $query = Notificacion::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($filtro === 'no_leidas') {
            $query->where('leido', false);
        }

        $notificaciones = $query->paginate(20);

        return view('padre.notificaciones', array_merge($data, compact('notificaciones', 'filtro')));
    }

    public function marcarLeida(Notificacion $notificacion)
    {
        abort_if($notificacion->user_id !== auth()->id(), 403);

        $notificacion->update(['leido' => true]);

        return back()->with('success', 'Notificación marcada como leída');
    }

    public function pagos()
    {
        $data = $this->getPadreData();
        $padre = $data['padre'];

        if (! $padre) {
            return view('padre.pagos', array_merge($data, [
                'pagosPorHijo' => collect(),
            ]));
        }

        $hijos = $padre->alumnos()->get();

        $pagosPorHijo = collect();
        foreach ($hijos as $hijo) {
            $pagos = Pago::where('alumno_id', $hijo->id)
                ->with('periodoAcademico')
                ->orderBy('fecha_vencimiento', 'desc')
                ->get();

            $pagosPorHijo->put($hijo->id, [
                'hijo' => $hijo,
                'pagos' => $pagos,
                'total_pagado' => $pagos->where('estado.value', 'pagado')->sum('monto'),
                'total_pendiente' => $pagos->whereIn('estado.value', ['pendiente', 'vencido'])->sum('monto'),
            ]);
        }

        return view('padre.pagos', array_merge($data, [
            'pagosPorHijo' => $pagosPorHijo,
        ]));
    }
}
