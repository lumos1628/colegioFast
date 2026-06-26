<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncidenciaConductaRequest;
use App\Jobs\EnviarNotificacionJob;
use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\IncidenciaConducta;
use App\Models\Matricula;

class IncidenciaConductaController extends Controller
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

    public function create(Asignacion $asignacion, Alumno $alumno)
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        Matricula::where('asignacion_id', $asignacion->id)
            ->where('alumno_id', $alumno->id)
            ->firstOrFail();

        return view('docente.incidencias.create', array_merge($data, compact('asignacion', 'alumno')));
    }

    public function store(StoreIncidenciaConductaRequest $request, Asignacion $asignacion, Alumno $alumno)
    {
        $docente = auth()->user()->docente;
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        Matricula::where('asignacion_id', $asignacion->id)
            ->where('alumno_id', $alumno->id)
            ->firstOrFail();

        $incidencia = IncidenciaConducta::create([
            'alumno_id' => $alumno->id,
            'docente_id' => $docente->id,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
        ]);

        EnviarNotificacionJob::dispatch(
            'incidencia',
            $alumno->id,
            null,
            null,
            $incidencia->id
        );

        return redirect()
            ->route('docente.cursos.alumnos.show', [$asignacion, $alumno])
            ->with('success', 'Incidencia registrada correctamente');
    }

    public function edit(Asignacion $asignacion, Alumno $alumno, IncidenciaConducta $incidencia)
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);
        abort_if($incidencia->alumno_id !== $alumno->id, 404);

        Matricula::where('asignacion_id', $asignacion->id)
            ->where('alumno_id', $alumno->id)
            ->firstOrFail();

        return view('docente.incidencias.edit', array_merge($data, compact('asignacion', 'alumno', 'incidencia')));
    }

    public function update(StoreIncidenciaConductaRequest $request, Asignacion $asignacion, Alumno $alumno, IncidenciaConducta $incidencia)
    {
        $docente = auth()->user()->docente;
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);
        abort_if($incidencia->alumno_id !== $alumno->id, 404);

        Matricula::where('asignacion_id', $asignacion->id)
            ->where('alumno_id', $alumno->id)
            ->firstOrFail();

        $incidencia->update([
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
        ]);

        return redirect()
            ->route('docente.cursos.alumnos.show', [$asignacion, $alumno])
            ->with('success', 'Incidencia actualizada correctamente');
    }

    public function destroy(Asignacion $asignacion, Alumno $alumno, IncidenciaConducta $incidencia)
    {
        $docente = auth()->user()->docente;
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);
        abort_if($incidencia->alumno_id !== $alumno->id, 404);

        Matricula::where('asignacion_id', $asignacion->id)
            ->where('alumno_id', $alumno->id)
            ->firstOrFail();

        $incidencia->delete();

        return redirect()
            ->route('docente.cursos.alumnos.show', [$asignacion, $alumno])
            ->with('success', 'Incidencia eliminada correctamente');
    }
}
