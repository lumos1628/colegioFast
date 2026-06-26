<x-docente-layout :docente="$docente" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[
            ['label' => 'Dashboard', 'url' => route('docente.dashboard')],
            ['label' => 'Actividades Pendientes']
        ]" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Actividades Pendientes</h1>
            <p class="mt-1 text-sm text-gray-500">
                Actividades con calificaciones incompletas o programadas a futuro
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1 min-w-0">
                @if($actividadesPendientes->isEmpty())
                    <x-alert type="success" title="Todo al día">
                        No tienes actividades pendientes. Todas las calificaciones están registradas.
                    </x-alert>
                @else
                    <div class="space-y-4">
                        @foreach($actividadesPendientes as $actividad)
                            @php
                                $totalAlumnos = $actividad->asignacion->matriculas->count();
                                $alumnosConNota = $actividad->notas->count();
                                $porcentaje = $totalAlumnos > 0 ? round(($alumnosConNota / $totalAlumnos) * 100) : 0;
                                $esFutura = $actividad->fecha->isFuture();
                            @endphp
                            <a href="{{ route('docente.cursos.actividades.show', [$actividad->asignacion, $actividad]) }}" class="block cursor-pointer">
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ $actividad->titulo }}</h3>
                                                    @if($esFutura)
                                                        <x-badge variant="info">Programada</x-badge>
                                                    @elseif($alumnosConNota < $totalAlumnos)
                                                        <x-badge variant="warning">Incompleta</x-badge>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    {{ $actividad->asignacion->curso->nombre }} - {{ $actividad->asignacion->curso->grado }}° "{{ $actividad->asignacion->curso->seccion }}"
                                                </p>
                                            </div>
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($actividad->competencia->nombre, 0, 2) }}
                                            </div>
                                        </div>

                                        <div class="mt-4 space-y-3">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600 flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $actividad->fecha->format('d/m/Y') }}
                                                </span>
                                                <span class="text-gray-600">
                                                    {{ $alumnosConNota }}/{{ $totalAlumnos }} alumnos calificados
                                                </span>
                                            </div>

                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full transition-all duration-300
                                                    {{ $porcentaje === 100 ? 'bg-green-500' : ($porcentaje > 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                                    style="width: {{ $porcentaje }}%">
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-purple-100 text-purple-700">
                                                    {{ $actividad->competencia->nombre }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $porcentaje }}% completado</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="w-full lg:w-80 shrink-0 lg:sticky top-8 self-start">
                <div class="space-y-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total pendientes</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $actividadesPendientes->count() }}</p>
                            </div>
                        </div>
                    </div>

                    @php
                        $futuras = $actividadesPendientes->filter(fn($a) => $a->fecha->isFuture())->count();
                        $incompletas = $actividadesPendientes->count() - $futuras;
                    @endphp

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Programadas</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $futuras }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Calificación incompleta</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $incompletas }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-docente-layout>
