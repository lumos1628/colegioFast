<x-docente-layout :docente="$docente" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Mis Cursos', 'url' => route('docente.dashboard')],
            ['label' => $asignacion->curso->nombre, 'url' => route('docente.cursos.show', $asignacion)],
            ['label' => 'Actividades', 'url' => route('docente.cursos.actividades.index', $asignacion)],
            ['label' => $actividad->titulo]
        ]" />

        {{-- Success Message --}}
        @if(session('success'))
            <x-alert type="success" class="mb-6" dismissible>
                {{ session('success') }}
            </x-alert>
        @endif

        {{-- Activity Details --}}
        <x-card class="mb-6">
            <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $actividad->titulo }}</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $asignacion->curso->nombre }} - {{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}"
                    </p>
                </div>
                <div class="flex gap-2 shrink-0">
                    <x-button variant="secondary" :href="route('docente.cursos.actividades.edit', [$asignacion, $actividad])">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </x-button>
                    <form action="{{ route('docente.cursos.actividades.destroy', [$asignacion, $actividad]) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta actividad? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <x-button variant="danger" type="submit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </x-button>
                    </form>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $actividad->fecha->format('d/m/Y') }}
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $actividad->competencia->nombre }}
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span class="truncate">{{ $actividad->capacidad->nombre }}</span>
                </div>
            </div>

            @if($actividad->descripcion)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-700">{{ $actividad->descripcion }}</p>
                </div>
            @endif
        </x-card>

        {{-- Grades Form --}}
        <form action="{{ route('docente.cursos.actividades.notas.store', [$asignacion, $actividad]) }}" method="POST">
            @csrf
            <x-card title="Calificaciones" :subtitle="$alumnos->count() . ' alumnos'">
                @if($alumnos->isEmpty())
                    <x-alert type="info">
                        No hay alumnos matriculados en este curso.
                    </x-alert>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alumno
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Calificación
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Observación
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Visible
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($alumnos as $index => $alumno)
                                    @php
                                        $notaExistente = $notas[$alumno->id] ?? null;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-xs">
                                                    {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellido_paterno, 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $alumno->nombres }} {{ $alumno->apellido_paterno }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">DNI: {{ $alumno->dni }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="notas[{{ $index }}][calificacion]"
                                                    class="block w-32 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                <option value="">Sin registrar</option>
                                                <option value="AD" {{ $notaExistente && $notaExistente->calificacion->value === 'AD' ? 'selected' : '' }}>AD - Logro destacado</option>
                                                <option value="A" {{ $notaExistente && $notaExistente->calificacion->value === 'A' ? 'selected' : '' }}>A - Logro esperado</option>
                                                <option value="B" {{ $notaExistente && $notaExistente->calificacion->value === 'B' ? 'selected' : '' }}>B - En proceso</option>
                                                <option value="C" {{ $notaExistente && $notaExistente->calificacion->value === 'C' ? 'selected' : '' }}>C - En inicio</option>
                                            </select>
                                            <input type="hidden" name="notas[{{ $index }}][alumno_id]" value="{{ $alumno->id }}">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="notas[{{ $index }}][observacion]"
                                                   value="{{ $notaExistente->observacion ?? '' }}"
                                                   placeholder="Observación opcional"
                                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="checkbox" name="notas[{{ $index }}][visible_para_alumno]" value="1"
                                                   {{ (!$notaExistente || $notaExistente->visible_para_alumno) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <x-button variant="primary" type="submit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Guardar Calificaciones
                        </x-button>
                    </div>
                @endif
            </x-card>
        </form>
    </div>
</x-docente-layout>
