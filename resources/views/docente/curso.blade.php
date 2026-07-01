<x-docente-layout :docente="$docente" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Mis Cursos', 'url' => route('docente.dashboard')],
            ['label' => $asignacion->curso->nombre . ' ' . $asignacion->curso->grado . '°' . $asignacion->curso->seccion]
        ]" />

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">{{ $asignacion->curso->nombre }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}" • {{ $asignacion->periodoAcademico->nombre }}
            </p>
        </div>

        {{-- Layout de dos columnas --}}
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Columna izquierda: Lista de alumnos --}}
            <div class="flex-1 min-w-0">
                <x-card title="Alumnos Matriculados" :subtitle="$asignacion->matriculas->count() . ' alumnos registrados'">
                    {{-- Search Input --}}
                    <div class="mb-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="buscar-alumno" placeholder="Buscar por nombre o DNI..."
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out">
                        </div>
                    </div>

                    @if($asignacion->matriculas->isEmpty())
                        <x-alert type="info">
                            No hay alumnos matriculados en este curso.
                        </x-alert>
                    @else
                        <div class="overflow-hidden">
                            <ul class="divide-y divide-gray-200" id="lista-alumnos">
                                @foreach($asignacion->matriculas as $matricula)
                                    <li class="alumno-item hover:bg-gray-50 transition-colors"
                                        data-nombre="{{ strtolower($matricula->alumno->nombres . ' ' . $matricula->alumno->apellido_paterno . ' ' . $matricula->alumno->apellido_materno) }}"
                                        data-dni="{{ $matricula->alumno->dni }}">
                                        <a href="{{ route('docente.cursos.alumnos.show', [$asignacion, $matricula->alumno]) }}" class="block px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                                                    {{ substr($matricula->alumno->nombres, 0, 1) }}{{ substr($matricula->alumno->apellido_paterno, 0, 1) }}
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $matricula->alumno->nombres }} {{ $matricula->alumno->apellido_paterno }} {{ $matricula->alumno->apellido_materno }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        DNI: {{ $matricula->alumno->dni }}
                                                    </p>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </x-card>
            </div>

            {{-- Columna derecha: Estadísticas --}}
            <div class="w-full lg:w-80 shrink-0 lg:sticky top-8 self-start">
                <div class="space-y-4">
                    <a href="{{ route('docente.cursos.asistencia.index', $asignacion) }}" class="block bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-4 flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-semibold text-gray-900">Registrar Asistencia</p>
                                <p class="text-xs text-gray-500 mt-0.5">Registra la asistencia del día</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="w-full flex items-center p-4">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-3 text-left">
                                <p class="text-sm font-semibold text-gray-900">Actividades</p>
                                <p class="text-xs text-gray-500">{{ $actividadesRecientes->count() }} actividades recientes</p>
                            </div>
                        </div>
                        <div id="lista-actividades" class="border-t border-gray-200">
                            @forelse($actividadesRecientes as $actividad)
                                <a href="{{ route('docente.cursos.actividades.show', [$asignacion, $actividad]) }}" class="block p-4 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $actividad->titulo }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $actividad->fecha->format('d/m/Y') }}</p>
                                        </div>
                                        <span class="ml-3 inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-700 max-w-24 truncate" title="{{ $actividad->competencia->nombre }}">
                                            {{ $actividad->competencia->nombre }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <div class="p-4 text-center">
                                    <p class="text-sm text-gray-500">Sin actividades creadas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <a href="{{ route('docente.cursos.actividades.create', $asignacion) }}" class="block bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-4 flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-semibold text-gray-900">Crear Actividad</p>
                                <p class="text-xs text-gray-500 mt-0.5">Crea una nueva actividad o evaluación</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('docente.cursos.actividades.index', $asignacion) }}" class="block bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-4 flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-semibold text-gray-900">Calificar Actividades</p>
                                <p class="text-xs text-gray-500 mt-0.5">Registra notas de actividades pendientes</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>

                    <div class="mt-6">
                        <a href="{{ route('docente.cursos.reporte', $asignacion) }}" class="block bg-blue-50/50 rounded-lg shadow-sm border-2 border-dashed border-blue-300 hover:border-blue-400 hover:bg-blue-50 transition-colors">
                            <div class="p-4 flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-semibold text-blue-900">Exportar Excel</p>
                                    <p class="text-xs text-blue-700 mt-0.5">Descarga el reporte completo del curso</p>
                                </div>
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Alumnos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $asignacion->matriculas->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Promedio General</p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    @php
                                        $promedio = $asignacion->matriculas->flatMap->alumno->flatMap->notas->avg(fn($nota) => $nota->calificacion->numericValue());
                                    @endphp
                                    {{ $promedio ? number_format($promedio, 1) : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('buscar-alumno').addEventListener('input', function(e) {
            const filtro = e.target.value.toLowerCase();
            document.querySelectorAll('.alumno-item').forEach(item => {
                const nombre = item.dataset.nombre;
                const dni = item.dataset.dni;
                item.style.display = (nombre.includes(filtro) || dni.includes(filtro)) ? '' : 'none';
            });
        });
    </script>
</x-docente-layout>
