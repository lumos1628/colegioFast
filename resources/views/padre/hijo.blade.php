<x-padre-layout :padre="$padre" :hijos="$hijos">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[
            ['label' => 'Mis Hijos', 'url' => route('padre.dashboard')],
            ['label' => $alumno->nombres . ' ' . $alumno->apellido_paterno]
        ]" />

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white font-bold text-xl">
                    {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellido_paterno, 0, 1) }}
                </div>
                <div class="ml-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}
                    </h1>
                    <div class="mt-2 flex flex-wrap gap-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            DNI: {{ $alumno->dni }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            {{ $alumno->grado }}° "{{ $alumno->seccion }}"
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1 min-w-0">
                <div class="space-y-6">
                    <x-card title="Progreso Bimestral" subtitle="Promedios por competencia (todas las notas)">
                        @if($progresoBimestral->isEmpty())
                            <x-alert type="info">
                                Sin datos de progreso bimestral.
                            </x-alert>
                        @else
                            <div class="space-y-4">
                                @foreach($progresoBimestral as $progreso)
                                    @php
                                        $nivel = match(true) {
                                            $progreso->promedio_numerico >= 3.5 => ['label' => 'AD', 'variant' => 'success', 'color' => 'bg-green-500'],
                                            $progreso->promedio_numerico >= 2.5 => ['label' => 'A', 'variant' => 'primary', 'color' => 'bg-blue-500'],
                                            $progreso->promedio_numerico >= 1.5 => ['label' => 'B', 'variant' => 'warning', 'color' => 'bg-yellow-500'],
                                            default => ['label' => 'C', 'variant' => 'danger', 'color' => 'bg-red-500'],
                                        };
                                        $porcentaje = round(($progreso->promedio_numerico / 4) * 100);
                                    @endphp
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $progreso->competencia->nombre }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5">
                                                    {{ $progreso->asignacion->curso->nombre ?? '' }} — {{ $progreso->total_notas }} evaluaciones
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg font-bold text-gray-900">{{ number_format($progreso->promedio_numerico, 1) }}</span>
                                                <x-badge :variant="$nivel['variant']">{{ $nivel['label'] }}</x-badge>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $nivel['color'] }} transition-all duration-300" style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-card>

                    <x-card title="Calificaciones Detalladas" subtitle="Todas las notas registradas (incluye evaluaciones internas)">
                        @if($notasPorCurso->isEmpty())
                            <x-alert type="info">
                                Sin calificaciones registradas.
                            </x-alert>
                        @else
                            @foreach($notasPorCurso as $asignacionId => $notas)
                                <div class="mb-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-violet-500 mr-2"></span>
                                        {{ $notas->first()->actividad->asignacion->curso->nombre }}
                                    </h4>
                                    <ul class="space-y-2">
                                        @foreach($notas as $nota)
                                            <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <p class="text-sm font-medium text-gray-900">{{ $nota->actividad->titulo }}</p>
                                                        @if(! $nota->visible_para_alumno)
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-600" title="Evaluación interna - no visible para el alumno">
                                                                Interna
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1">{{ $nota->actividad->competencia->nombre }}</p>
                                                </div>
                                                <x-badge :variant="match($nota->calificacion->value) {
                                                    'AD' => 'success',
                                                    'A' => 'primary',
                                                    'B' => 'warning',
                                                    'C' => 'danger',
                                                    default => 'default'
                                                }">
                                                    {{ $nota->calificacion->value }} - {{ $nota->calificacion->label() }}
                                                </x-badge>
                                            </li>
                                            @if($nota->observacion)
                                                <li class="px-3 pb-2">
                                                    <p class="text-xs text-gray-500 italic">{{ $nota->observacion }}</p>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @endif
                    </x-card>

                    <x-card title="Asistencias" :subtitle="$asistencias->count() . ' registros'">
                        @if($asistencias->isEmpty())
                            <x-alert type="info">
                                Sin registros de asistencia.
                            </x-alert>
                        @else
                            <ul class="space-y-2">
                                @foreach($asistencias->take(15) as $asistencia)
                                    <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <span class="text-sm text-gray-700">{{ $asistencia->fecha->format('d/m/Y') }}</span>
                                            <span class="text-xs text-gray-500 ml-2">{{ $asistencia->asignacion->curso->nombre }}</span>
                                        </div>
                                        <x-badge :variant="match($asistencia->estado->value) {
                                            'presente' => 'success',
                                            'tardanza' => 'warning',
                                            'ausente' => 'danger',
                                            'justificado' => 'info',
                                            default => 'default'
                                        }">
                                            {{ $asistencia->estado->label() }}
                                        </x-badge>
                                    </li>
                                @endforeach
                            </ul>
                            @if($asistencias->count() > 15)
                                <p class="text-sm text-gray-500 text-center mt-3">
                                    Mostrando 15 de {{ $asistencias->count() }} registros
                                </p>
                            @endif
                        @endif
                    </x-card>

                    <x-card title="Incidencias de Conducta" :subtitle="$incidencias->count() . ' registros'">
                        @if($incidencias->isEmpty())
                            <x-alert type="info">
                                Sin incidencias registradas.
                            </x-alert>
                        @else
                            <ul class="space-y-3">
                                @foreach($incidencias as $incidencia)
                                    <li class="p-4 border border-gray-200 rounded-lg">
                                        <x-badge :variant="match($incidencia->tipo->value) {
                                            'falta_leve' => 'warning',
                                            'falta_grave' => 'danger',
                                            'merito' => 'success',
                                            default => 'default'
                                        }">
                                            {{ $incidencia->tipo->label() }}
                                        </x-badge>
                                        <p class="text-sm text-gray-700 mt-2">{{ $incidencia->descripcion }}</p>
                                        <span class="text-xs text-gray-500 mt-2 block">{{ $incidencia->fecha->format('d/m/Y') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </x-card>
                </div>
            </div>

            <div class="w-full lg:w-80 shrink-0 lg:sticky top-8 self-start">
                <div class="space-y-4">
                    @php
                        $totalNotas = $notasPorCurso->flatten()->count();
                        $promedioGeneral = $progresoBimestral->avg('promedio_numerico');
                    @endphp

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-violet-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Calificaciones</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalNotas }}</p>
                            </div>
                        </div>
                    </div>

                    @if($promedioGeneral !== null)
                        @php
                            $nivelGeneral = match(true) {
                                $promedioGeneral >= 3.5 => ['label' => 'AD', 'variant' => 'success'],
                                $promedioGeneral >= 2.5 => ['label' => 'A', 'variant' => 'primary'],
                                $promedioGeneral >= 1.5 => ['label' => 'B', 'variant' => 'warning'],
                                default => ['label' => 'C', 'variant' => 'danger'],
                            };
                        @endphp
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Promedio general</p>
                                    <div class="flex items-center gap-2">
                                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($promedioGeneral, 1) }}</p>
                                        <x-badge :variant="$nivelGeneral['variant']">{{ $nivelGeneral['label'] }}</x-badge>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Asistencias</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $asistencias->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Incidencias</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $incidencias->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-padre-layout>
