<x-alumno-layout :alumno="$alumno" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Mis Cursos</h1>
            <p class="mt-1 text-sm text-gray-500">
                Periodo académico activo — {{ $matriculas->first()?->asignacion?->periodoAcademico?->nombre ?? 'Sin periodo' }}
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1 min-w-0">
                @if($matriculas->isEmpty())
                    <x-alert type="warning" title="Sin cursos matriculados">
                        No tienes cursos matriculados en el periodo activo.
                    </x-alert>
                @else
                    <div class="space-y-4">
                        @foreach($matriculas as $matricula)
                            @php $asignacion = $matricula->asignacion; @endphp
                            <a href="{{ route('alumno.cursos.show', $asignacion) }}" class="block cursor-pointer">
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $asignacion->curso->nombre }}</h3>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    {{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}"
                                                </p>
                                            </div>
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($asignacion->curso->nombre, 0, 2) }}
                                            </div>
                                        </div>

                                        <div class="mt-4 space-y-2">
                                            @if($asignacion->dia_semana)
                                                @php
                                                    $diasSemana = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes'];
                                                @endphp
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $diasSemana[$asignacion->dia_semana] ?? '' }}
                                                    @if($asignacion->hora_inicio && $asignacion->hora_fin)
                                                        {{ \Carbon\Carbon::parse($asignacion->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($asignacion->hora_fin)->format('H:i') }}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="w-full lg:w-80 shrink-0 lg:sticky top-8 self-start">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 max-h-[calc(100vh-6rem)] overflow-y-auto">
                    <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Mi progreso</h3>
                        <p class="text-sm text-gray-500 mt-1">Promedios por competencia</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($progresoBimestral as $progreso)
                            @php
                                $nivel = match(true) {
                                    $progreso->promedio_numerico >= 3.5 => ['label' => 'AD', 'variant' => 'success', 'color' => 'bg-green-500'],
                                    $progreso->promedio_numerico >= 2.5 => ['label' => 'A', 'variant' => 'primary', 'color' => 'bg-blue-500'],
                                    $progreso->promedio_numerico >= 1.5 => ['label' => 'B', 'variant' => 'warning', 'color' => 'bg-yellow-500'],
                                    default => ['label' => 'C', 'variant' => 'danger', 'color' => 'bg-red-500'],
                                };
                                $porcentaje = round(($progreso->promedio_numerico / 4) * 100);
                            @endphp
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $progreso->competencia->nombre }}</p>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-sm font-bold text-gray-900">{{ number_format($progreso->promedio_numerico, 1) }}</span>
                                        <x-badge :variant="$nivel['variant']">{{ $nivel['label'] }}</x-badge>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $nivel['color'] }}" style="width: {{ $porcentaje }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $asignacion->curso->nombre ?? '' }}
                                </p>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Aún no hay calificaciones</p>
                                <p class="text-xs text-gray-400 mt-1">Las notas aparecerán aquí cuando el docente las registre</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-alumno-layout>
