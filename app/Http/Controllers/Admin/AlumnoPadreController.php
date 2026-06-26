<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Padre;
use Illuminate\Http\Request;

class AlumnoPadreController extends Controller
{
    public function index(Alumno $alumno)
    {
        $alumno->load(['padres']);
        $padresDisponibles = Padre::whereDoesntHave('alumnos', function ($q) use ($alumno) {
            $q->where('alumnos.id', $alumno->id);
        })->orderBy('apellido_paterno')->orderBy('nombres')->get();

        return view('administrativo.alumno-padre.index', compact('alumno', 'padresDisponibles'));
    }

    public function store(Request $request, Alumno $alumno)
    {
        $data = $request->validate([
            'padre_id' => ['required', 'exists:padres,id'],
            'parentesco' => ['required', 'in:padre,madre,tutor'],
        ]);

        $yaVinculado = $alumno->padres()->where('padres.id', $data['padre_id'])->exists();

        if ($yaVinculado) {
            return redirect()
                ->route('admin.alumno-padre.index', $alumno)
                ->with('warning', 'Este padre ya está vinculado al alumno');
        }

        $alumno->padres()->attach($data['padre_id'], [
            'parentesco' => $data['parentesco'],
        ]);

        return redirect()
            ->route('admin.alumno-padre.index', $alumno)
            ->with('success', 'Padre vinculado correctamente');
    }

    public function destroy(Alumno $alumno, Padre $padre)
    {
        $alumno->padres()->detach($padre->id);

        return redirect()
            ->route('admin.alumno-padre.index', $alumno)
            ->with('success', 'Vinculación eliminada correctamente');
    }
}
