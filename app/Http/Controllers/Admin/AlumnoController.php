<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumno::query()->orderBy('apellido_paterno')->orderBy('nombres');

        if ($busqueda = $request->input('busqueda')) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombres', 'like', "%{$busqueda}%")
                    ->orWhere('apellido_paterno', 'like', "%{$busqueda}%")
                    ->orWhere('apellido_materno', 'like', "%{$busqueda}%")
                    ->orWhere('dni', 'like', "%{$busqueda}%");
            });
        }

        if ($grado = $request->input('grado')) {
            $query->where('grado', $grado);
        }

        $alumnos = $query->paginate(20)->withQueryString();

        return view('administrativo.alumnos.index', compact('alumnos', 'busqueda', 'grado'));
    }

    public function create()
    {
        return view('administrativo.alumnos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date'],
            'dni' => ['required', 'string', 'size:8', 'unique:alumnos,dni'],
            'grado' => ['required', 'integer', 'min:1', 'max:6'],
            'seccion' => ['required', 'string', 'max:1'],
            'email' => ['nullable', 'email', 'unique:users,email'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => "{$data['nombres']} {$data['apellido_paterno']}",
                'email' => $data['email'] ?? null,
                'password' => Hash::make('password'),
                'role' => UserRole::Alumno,
            ]);

            Alumno::create([
                'user_id' => $user->id,
                'nombres' => $data['nombres'],
                'apellido_paterno' => $data['apellido_paterno'],
                'apellido_materno' => $data['apellido_materno'],
                'fecha_nacimiento' => $data['fecha_nacimiento'],
                'dni' => $data['dni'],
                'grado' => $data['grado'],
                'seccion' => $data['seccion'],
            ]);
        });

        return redirect()
            ->route('admin.alumnos.index')
            ->with('success', 'Alumno creado correctamente');
    }

    public function edit(Alumno $alumno)
    {
        return view('administrativo.alumnos.edit', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $data = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date'],
            'dni' => ['required', 'string', 'size:8', Rule::unique('alumnos', 'dni')->ignore($alumno->id)],
            'grado' => ['required', 'integer', 'min:1', 'max:6'],
            'seccion' => ['required', 'string', 'max:1'],
        ]);

        $alumno->update($data);

        if ($alumno->user) {
            $alumno->user->update([
                'name' => "{$data['nombres']} {$data['apellido_paterno']}",
            ]);
        }

        return redirect()
            ->route('admin.alumnos.index')
            ->with('success', 'Alumno actualizado correctamente');
    }

    public function destroy(Alumno $alumno)
    {
        DB::transaction(function () use ($alumno) {
            if ($alumno->user) {
                $alumno->user->delete();
            }
            $alumno->delete();
        });

        return redirect()
            ->route('admin.alumnos.index')
            ->with('success', 'Alumno eliminado correctamente');
    }
}
