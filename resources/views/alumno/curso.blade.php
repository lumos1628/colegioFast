<x-alumno-layout :alumno="$alumno" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[
            ['label' => 'Mis Cursos', 'url' => route('alumno.dashboard')],
            ['label' => $asignacion->curso->nombre]
        ]" />

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-xl">
                    {{ substr($asignacion->curso->nombre, 0, 2) }}
                </div>
                <div class="ml-6">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $asignacion->curso->nombre }}</h1>
                    <div class="mt-2 flex flex-wrap gap-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            {{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}"
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $asignacion->periodoAcademico->nombre }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1 min-w-0">
                <x-card title="Actividades" :subtitle="$actividades->count() . ' actividades'">
                    @if($actividades->isEmpty())
                        <x-alert type="info">
                            No hay actividades registradas en este curso.
                        </x-alert>
                    @else
                        <div class="space-y-4">
                            @foreach($actividades as $actividad)
                                @php
                                    $miNota = $actividad->notas->first();
                                @endphp
                                <div class="p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-gray-900">{{ $actividad->titulo }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">{{ $actividad->competencia->nombre }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $actividad->fecha->format('d/m/Y') }}</p>
                                        </div>
                                        @if($miNota)
                                            <x-badge :variant="match($miNota->calificacion->value) {
                                                'AD' => 'success',
                                                'A' => 'primary',
                                                'B' => 'warning',
                                                'C' => 'danger',
                                                default => 'default'
                                            }">
                                                {{ $miNota->calificacion->value }} - {{ $miNota->calificacion->label() }}
                                            </x-badge>
                                        @else
                                            <x-badge variant="default">Sin calificar</x-badge>
                                        @endif
                                    </div>
                                    @if($miNota && $miNota->observacion)
                                        <p class="text-xs text-gray-500 italic mt-2">{{ $miNota->observacion }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>
            </div>

            <div class="w-full lg:w-80 shrink-0 lg:sticky top-8 self-start">
                <div class="space-y-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Actividades</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $actividades->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Calificadas</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $notas->count() }}</p>
                            </div>
                        </div>
                    </div>

                    @php
                        $sinCalificar = $actividades->count() - $notas->count();
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Sin calificar</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $sinCalificar }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-alumno-layout>
