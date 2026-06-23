<x-portal-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <a href="{{ route('docente.cursos.actividades.index', $asignacion) }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Volver a actividades
        </a>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-3xl font-bold mb-2">{{ $actividad->titulo }}</h1>
            <p class="text-gray-600 mb-2">{{ $asignacion->curso->nombre }} - {{ $asignacion->curso->grado }} {{ $asignacion->curso->seccion }}</p>
            <p class="text-sm text-gray-500 mb-1">Competencia: {{ $actividad->competencia->nombre }}</p>
            <p class="text-sm text-gray-500 mb-1">Capacidad: {{ $actividad->capacidad->nombre }}</p>
            <p class="text-sm text-gray-500">Fecha: {{ $actividad->fecha->format('d/m/Y') }}</p>
            @if($actividad->descripcion)
                <p class="text-gray-600 mt-3">{{ $actividad->descripcion }}</p>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('docente.cursos.actividades.notas.store', [$asignacion, $actividad]) }}" method="POST">
            @csrf

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-xl font-semibold">Calificaciones ({{ $alumnos->count() }} alumnos)</h2>
                </div>

                @if($alumnos->isEmpty())
                    <div class="p-6 text-gray-500">No hay alumnos matriculados en este curso.</div>
                @else
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumno</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Calificación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observación</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($alumnos as $index => $alumno)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <p class="font-medium">{{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</p>
                                        <p class="text-sm text-gray-500">DNI: {{ $alumno->dni }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="notas[{{ $index }}][calificacion]"
                                                class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Sin registrar</option>
                                            <option value="AD" {{ isset($notas[$alumno->id]) && $notas[$alumno->id]->calificacion->value === 'AD' ? 'selected' : '' }}>AD - Logro destacado</option>
                                            <option value="A" {{ isset($notas[$alumno->id]) && $notas[$alumno->id]->calificacion->value === 'A' ? 'selected' : '' }}>A - Logro esperado</option>
                                            <option value="B" {{ isset($notas[$alumno->id]) && $notas[$alumno->id]->calificacion->value === 'B' ? 'selected' : '' }}>B - En proceso</option>
                                            <option value="C" {{ isset($notas[$alumno->id]) && $notas[$alumno->id]->calificacion->value === 'C' ? 'selected' : '' }}>C - En inicio</option>
                                        </select>
                                        <input type="hidden" name="notas[{{ $index }}][alumno_id]" value="{{ $alumno->id }}">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="notas[{{ $index }}][observacion]"
                                               value="{{ $notas[$alumno->id]->observacion ?? '' }}"
                                               placeholder="Observación opcional"
                                               class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="border-t border-gray-200 px-6 py-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                            Guardar calificaciones
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
</x-portal-layout>
