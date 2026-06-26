<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Padre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PadreController extends Controller
{
    public function index(Request $request)
    {
        $query = Padre::query()->orderBy('apellido_paterno')->orderBy('nombres');

        if ($busqueda = $request->input('busqueda')) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombres', 'like', "%{$busqueda}%")
                    ->orWhere('apellido_paterno', 'like', "%{$busqueda}%")
                    ->orWhere('apellido_materno', 'like', "%{$busqueda}%")
                    ->orWhere('dni', 'like', "%{$busqueda}%");
            });
        }

        $padres = $query->paginate(20)->withQueryString();

        return view('administrativo.padres.index', compact('padres', 'busqueda'));
    }

    public function create()
    {
        return view('administrativo.padres.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'size:8', 'unique:padres,dni'],
            'telefono' => ['nullable', 'string', 'max:15'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => "{$data['nombres']} {$data['apellido_paterno']}",
                'email' => $data['email'] ?? null,
                'password' => Hash::make('password'),
                'role' => UserRole::Padre,
            ]);

            Padre::create([
                'user_id' => $user->id,
                'nombres' => $data['nombres'],
                'apellido_paterno' => $data['apellido_paterno'],
                'apellido_materno' => $data['apellido_materno'],
                'dni' => $data['dni'],
                'telefono' => $data['telefono'] ?? null,
                'direccion' => $data['direccion'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.padres.index')
            ->with('success', 'Padre creado correctamente');
    }

    public function edit(Padre $padre)
    {
        return view('administrativo.padres.edit', compact('padre'));
    }

    public function update(Request $request, Padre $padre)
    {
        $data = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'size:8', Rule::unique('padres', 'dni')->ignore($padre->id)],
            'telefono' => ['nullable', 'string', 'max:15'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        $padre->update($data);

        if ($padre->user) {
            $padre->user->update([
                'name' => "{$data['nombres']} {$data['apellido_paterno']}",
            ]);
        }

        return redirect()
            ->route('admin.padres.index')
            ->with('success', 'Padre actualizado correctamente');
    }

    public function destroy(Padre $padre)
    {
        DB::transaction(function () use ($padre) {
            if ($padre->user) {
                $padre->user->delete();
            }
            $padre->delete();
        });

        return redirect()
            ->route('admin.padres.index')
            ->with('success', 'Padre eliminado correctamente');
    }
}
