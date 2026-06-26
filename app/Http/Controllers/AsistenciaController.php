<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAsistenciaRequest;
use App\Jobs\EnviarNotificacionJob;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Matricula;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
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

    public function index(Asignacion $asignacion, Request $request)
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];
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

        return view('docente.asistencia.index', array_merge($data, compact('asignacion', 'alumnos', 'asistencias', 'fecha')));
    }

    public function store(StoreAsistenciaRequest $request, Asignacion $asignacion)
    {
        $docente = auth()->user()->docente;
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        $fecha = $request->fecha;

        foreach ($request->asistencias as $asistenciaData) {
            $asistencia = Asistencia::updateOrCreate(
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

            if (in_array($asistenciaData['estado'], ['ausente', 'tardanza'])) {
                EnviarNotificacionJob::dispatch(
                    'inasistencia',
                    $asistenciaData['alumno_id'],
                    $asistencia->id
                );
            }
        }

        return redirect()
            ->route('docente.cursos.asistencia.index', [$asignacion, 'fecha' => $fecha])
            ->with('success', 'Asistencia guardada correctamente');
    }
}
