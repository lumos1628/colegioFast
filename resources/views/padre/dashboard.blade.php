<x-padre-layout :padre="$padre" :hijos="$hijos">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Panel de Padres</h1>
            <p class="mt-1 text-sm text-gray-500">
                Seguimiento académico de tus hijos
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1 min-w-0">
                @if($hijos->isEmpty())
                    <x-alert type="warning" title="Sin hijos registrados">
                        No tienes hijos vinculados a tu cuenta.
                    </x-alert>
                @else
                    <div class="space-y-4">
                        @foreach($hijos as $hijo)
                            @php
                                $dataHijo = $progresoPorHijo[$hijo->id] ?? ['hijo' => $hijo, 'progreso' => collect()];
                                $progreso = $dataHijo['progreso'];
                                $promedioGeneral = $progreso->avg('promedio_numerico');
                            @endphp
                            <a href="{{ route('padre.hijos.show', $hijo) }}" class="block cursor-pointer">
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center">
                                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white font-bold text-lg">
                                                    {{ substr($hijo->nombres, 0, 1) }}{{ substr($hijo->apellido_paterno, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ $hijo->nombres }} {{ $hijo->apellido_paterno }} {{ $hijo->apellido_materno }}</h3>
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        {{ $hijo->grado }}° "{{ $hijo->seccion }}"
                                                    </p>
                                                </div>
                                            </div>
                                            @if($promedioGeneral !== null)
                                                @php
                                                    $nivel = match(true) {
                                                        $promedioGeneral >= 3.5 => ['label' => 'AD', 'variant' => 'success'],
                                                        $promedioGeneral >= 2.5 => ['label' => 'A', 'variant' => 'primary'],
                                                        $promedioGeneral >= 1.5 => ['label' => 'B', 'variant' => 'warning'],
                                                        default => ['label' => 'C', 'variant' => 'danger'],
                                                    };
                                                @endphp
                                                <div class="text-right">
                                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($promedioGeneral, 1) }}</p>
                                                    <x-badge :variant="$nivel['variant']">{{ $nivel['label'] }}</x-badge>
                                                </div>
                                            @endif
                                        </div>

                                        @if($progreso->isNotEmpty())
                                            <div class="mt-4 flex flex-wrap gap-2">
                                                @foreach($progreso->take(4) as $p)
                                                    @php
                                                        $nivelP = match(true) {
                                                            $p->promedio_numerico >= 3.5 => 'bg-green-100 text-green-700',
                                                            $p->promedio_numerico >= 2.5 => 'bg-blue-100 text-blue-700',
                                                            $p->promedio_numerico >= 1.5 => 'bg-yellow-100 text-yellow-700',
                                                            default => 'bg-red-100 text-red-700',
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $nivelP }}">
                                                        {{ $p->competencia->nombre }}: {{ number_format($p->promedio_numerico, 1) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
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
                        <h3 class="text-lg font-semibold text-gray-900">Tareas pendientes</h3>
                        <p class="text-sm text-gray-500 mt-1">Ordenadas por fecha de vencimiento</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($actividadesPendientes as $actividad)
                            @php
                                $hijosSinNota = $actividad->notas->pluck('alumno_id')->unique();
                                $hijosPendientes = $hijos->filter(fn ($h) => !$hijosSinNota->contains($h->id));
                            @endphp
                            <div class="p-4">
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
                                        @if($hijosPendientes->isNotEmpty())
                                            <p class="text-xs text-violet-600 mt-1">
                                                {{ $hijosPendientes->pluck('nombres')->implode(', ') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-violet-100 text-violet-700">
                                            {{ $actividad->competencia->nombre }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay tareas pendientes</p>
                                <p class="text-xs text-gray-400 mt-1">Todas las actividades están al día</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-padre-layout>
