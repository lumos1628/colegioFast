<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAsistenciaRequest;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Matricula;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index(Asignacion $asignacion, Request $request)
    {
        $docente = auth()->user()->docente;
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        $fecha = $request->input('fecha', now()->format('Y-m-d'));

        $alumnos = Matricula::where('asignacion_id', $asignacion->id)
            ->with('alumno')
            ->get()
            ->pluck('alumno');

        $asistencias = Asistencia::where('asignacion_id', $asignacion->id)
            ->where('fecha', $fecha)
            ->get()
            ->keyBy('alumno_id');

        return view('docente.asistencia.index', compact('asignacion', 'alumnos', 'asistencias', 'fecha'));
    }

    public function store(StoreAsistenciaRequest $request, Asignacion $asignacion)
    {
        $docente = auth()->user()->docente;
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        $fecha = $request->fecha;

        foreach ($request->asistencias as $asistenciaData) {
            Asistencia::updateOrCreate(
                [
                    'asignacion_id' => $asignacion->id,
                    'alumno_id' => $asistenciaData['alumno_id'],
                    'fecha' => $fecha,
                ],
                [
                    'estado' => $asistenciaData['estado'],
                    'observacion' => $asistenciaData['observacion'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('docente.cursos.asistencia.index', [$asignacion, 'fecha' => $fecha])
            ->with('success', 'Asistencia guardada correctamente');
    }
}
