<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActividadRequest;
use App\Models\Actividad;
use App\Models\Asignacion;
use App\Models\Competencia;
use App\Models\Matricula;
use App\Models\Nota;

class ActividadController extends Controller
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

    public function index(Asignacion $asignacion)
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        $actividades = Actividad::where('asignacion_id', $asignacion->id)
            ->with(['competencia', 'capacidad', 'notas'])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('docente.actividades.index', array_merge($data, compact('asignacion', 'actividades')));
    }

    public function create(Asignacion $asignacion)
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        $competencias = Competencia::with('capacidades')->get();

        return view('docente.actividades.create', array_merge($data, compact('asignacion', 'competencias')));
    }

    public function store(StoreActividadRequest $request, Asignacion $asignacion)
    {
        $docente = auth()->user()->docente;
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);

        $actividad = Actividad::create([
            'asignacion_id' => $asignacion->id,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'competencia_id' => $request->competencia_id,
            'capacidad_id' => $request->capacidad_id,
        ]);

        return redirect()
            ->route('docente.cursos.actividades.show', [$asignacion, $actividad])
            ->with('success', 'Actividad creada correctamente');
    }

    public function show(Asignacion $asignacion, Actividad $actividad)
    {
        $data = $this->getDocenteData();
        $docente = $data['docente'];
        abort_if(! $docente || $asignacion->docente_id !== $docente->id, 403);
        abort_if($actividad->asignacion_id !== $asignacion->id, 404);

        $actividad->load(['competencia', 'capacidad']);

        $alumnos = Matricula::where('asignacion_id', $asignacion->id)
            ->with('alumno')
            ->get()
            ->pluck('alumno');

        $notas = Nota::where('actividad_id', $actividad->id)
            ->get()
            ->keyBy('alumno_id');

        return view('docente.actividades.show', array_merge($data, compact('asignacion', 'actividad', 'alumnos', 'notas')));
    }
}
