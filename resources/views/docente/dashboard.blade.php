<x-docente-layout :docente="$docente" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $esFinDeSemana ? 'Mis Cursos del Lunes' : 'Mis Cursos de Hoy' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $fecha->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
        </div>

        {{-- Layout de dos columnas: Cursos izquierda, Estadísticas derecha --}}
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Columna izquierda: Cursos --}}
            <div class="flex-1 min-w-0">
                @if($asignaciones->isEmpty())
                    <x-alert type="warning" :title="$esFinDeSemana ? 'Sin clases el lunes' : 'Sin clases hoy'">
                        {{ $esFinDeSemana ? 'No tienes clases programadas para el lunes.' : 'No tienes clases programadas para hoy. Disfruta tu día libre.' }}
                    </x-alert>
                @else
                    <div class="space-y-4">
                        @foreach($asignaciones as $asignacion)
                            <a href="{{ route('docente.cursos.show', $asignacion) }}" class="block cursor-pointer">
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $asignacion->curso->nombre }}</h3>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    {{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}"
                                                </p>
                                            </div>
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($asignacion->curso->nombre, 0, 2) }}
                                            </div>
                                        </div>

                                        <div class="mt-4 space-y-2">
                                            @if($asignacion->hora_inicio && $asignacion->hora_fin)
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($asignacion->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($asignacion->hora_fin)->format('H:i') }}
                                                </div>
                                            @endif
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                {{ $asignacion->matriculas->count() }} alumnos
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Columna derecha: Actividades recientes --}}
            <div class="w-full lg:w-80 shrink-0 lg:sticky top-8 self-start">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 max-h-[calc(100vh-6rem)] overflow-y-auto">
                    <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tareas pendientes</h3>
                        <p class="text-sm text-gray-500 mt-1">Ordenadas por fecha de vencimiento</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($actividadesPendientes as $actividad)
                            <a href="{{ route('docente.cursos.actividades.show', [$actividad->asignacion, $actividad]) }}" class="block p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $actividad->titulo }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $actividad->asignacion->curso->nombre }} - {{ $actividad->asignacion->curso->grado }}°{{ $actividad->asignacion->curso->seccion }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="text-xs text-gray-400">{{ $actividad->fecha->format('d/m/Y') }}</p>
                                            @if($actividad->fecha->isToday())
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">Hoy</span>
                                            @elseif($actividad->fecha->lte(now()->addDays(7)))
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-700">Esta semana</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-3 shrink-0">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-700 max-w-24 truncate" title="{{ $actividad->competencia->nombre }}">
                                            {{ $actividad->competencia->nombre }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No tienes tareas pendientes</p>
                                <p class="text-xs text-gray-400 mt-1">Todas tus actividades están al día</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-docente-layout>
