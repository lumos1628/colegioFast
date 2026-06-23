<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotaRequest;
use App\Models\Actividad;
use App\Models\Asignacion;
use App\Models\Nota;

class NotaController extends Controller
{
    public function storeOrUpdate(StoreNotaRequest $request, Asignacion $asignacion, Actividad $actividad)
    {
        $docente = auth()->user()->docente;
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);
        abort_if($actividad->asignacion_id !== $asignacion->id, 404);

        foreach ($request->notas as $notaData) {
            Nota::updateOrCreate(
                [
                    'actividad_id' => $actividad->id,
                    'alumno_id' => $notaData['alumno_id'],
                ],
                [
                    'calificacion' => $notaData['calificacion'],
                    'observacion' => $notaData['observacion'] ?? null,
                    'visible_para_alumno' => true,
                ]
            );
        }

        return redirect()
            ->route('docente.cursos.actividades.show', [$asignacion, $actividad])
            ->with('success', 'Calificaciones guardadas correctamente');
    }
}
