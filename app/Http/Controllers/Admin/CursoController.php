<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $query = Curso::query()->orderBy('grado')->orderBy('seccion')->orderBy('nombre');

        if ($grado = $request->input('grado')) {
            $query->where('grado', $grado);
        }

        $cursos = $query->paginate(20)->withQueryString();

        return view('administrativo.cursos.index', compact('cursos', 'grado'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'area_curricular' => ['required', 'string', 'max:255'],
            'grado' => ['required', 'integer', 'min:1', 'max:6'],
            'seccion' => ['required', 'string', 'max:1'],
        ]);

        Curso::create($data);

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Curso creado correctamente');
    }

    public function update(Request $request, Curso $curso)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'area_curricular' => ['required', 'string', 'max:255'],
            'grado' => ['required', 'integer', 'min:1', 'max:6'],
            'seccion' => ['required', 'string', 'max:1'],
        ]);

        $curso->update($data);

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Curso actualizado correctamente');
    }

    public function destroy(Curso $curso)
    {
        if ($curso->asignaciones()->exists()) {
            return redirect()
                ->route('admin.cursos.index')
                ->with('error', 'No se puede eliminar un curso con asignaciones');
        }

        $curso->delete();

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Curso eliminado correctamente');
    }
}
