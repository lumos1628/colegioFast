<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\BitacoraPsicologica;
use Illuminate\Http\Request;

class PsicologoController extends Controller
{
    private function getPsicologoData(): array
    {
        $psicologo = auth()->user();

        if (! $psicologo) {
            return ['psicologo' => null, 'alumnosAtendidos' => collect()];
        }

        $alumnosAtendidos = Alumno::whereHas('bitacoraPsicologica', function ($q) use ($psicologo) {
            $q->where('psicologo_id', $psicologo->id);
        })->orderBy('apellido_paterno')->orderBy('nombres')->get();

        return [
            'psicologo' => $psicologo,
            'alumnosAtendidos' => $alumnosAtendidos,
        ];
    }

    public function dashboard()
    {
        $data = $this->getPsicologoData();
        $psicologo = $data['psicologo'];

        if (! $psicologo) {
            return view('backoffice.psicologo.dashboard', array_merge($data, [
                'totalBitacoras' => 0,
                'totalAlumnos' => 0,
                'bitacorasRecientes' => collect(),
            ]));
        }

        $totalBitacoras = BitacoraPsicologica::where('psicologo_id', $psicologo->id)->count();
        $totalAlumnos = $data['alumnosAtendidos']->count();

        $bitacorasRecientes = BitacoraPsicologica::where('psicologo_id', $psicologo->id)
            ->with('alumno')
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get();

        return view('backoffice.psicologo.dashboard', array_merge($data, compact(
            'totalBitacoras',
            'totalAlumnos',
            'bitacorasRecientes'
        )));
    }

    public function bitacoras(Request $request)
    {
        $data = $this->getPsicologoData();
        $psicologo = $data['psicologo'];

        if (! $psicologo) {
            return view('backoffice.psicologo.bitacoras.index', array_merge($data, [
                'bitacoras' => collect(),
            ]));
        }

        $query = BitacoraPsicologica::where('psicologo_id', $psicologo->id)
            ->with('alumno')
            ->orderBy('fecha', 'desc');

        if ($alumnoId = $request->input('alumno')) {
            $query->where('alumno_id', $alumnoId);
        }

        $bitacoras = $query->paginate(15)->withQueryString();

        return view('backoffice.psicologo.bitacoras.index', array_merge($data, compact('bitacoras')));
    }

    public function create()
    {
        $data = $this->getPsicologoData();
        $alumnos = Alumno::orderBy('apellido_paterno')->orderBy('nombres')->get();

        return view('backoffice.psicologo.bitacoras.create', array_merge($data, compact('alumnos')));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'alumno_id' => ['required', 'exists:alumnos,id'],
            'fecha' => ['required', 'date'],
            'observaciones' => ['required', 'string', 'min:10'],
        ]);

        $data['psicologo_id'] = auth()->id();

        BitacoraPsicologica::create($data);

        return redirect()
            ->route('psicologo.bitacoras.index')
            ->with('success', 'Bitácora registrada correctamente');
    }

    public function edit(BitacoraPsicologica $bitacora)
    {
        abort_if($bitacora->psicologo_id !== auth()->id(), 403);

        $data = $this->getPsicologoData();
        $alumnos = Alumno::orderBy('apellido_paterno')->orderBy('nombres')->get();

        return view('backoffice.psicologo.bitacoras.edit', array_merge($data, compact('bitacora', 'alumnos')));
    }

    public function update(Request $request, BitacoraPsicologica $bitacora)
    {
        abort_if($bitacora->psicologo_id !== auth()->id(), 403);

        $data = $request->validate([
            'alumno_id' => ['required', 'exists:alumnos,id'],
            'fecha' => ['required', 'date'],
            'observaciones' => ['required', 'string', 'min:10'],
        ]);

        $bitacora->update($data);

        return redirect()
            ->route('psicologo.bitacoras.index')
            ->with('success', 'Bitácora actualizada correctamente');
    }

    public function destroy(BitacoraPsicologica $bitacora)
    {
        abort_if($bitacora->psicologo_id !== auth()->id(), 403);

        $bitacora->delete();

        return redirect()
            ->route('psicologo.bitacoras.index')
            ->with('success', 'Bitácora eliminada correctamente');
    }
}
