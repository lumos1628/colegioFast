<x-docente-layout :docente="$docente" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Horario Semanal</h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ auth()->user()->name }} • Periodo: {{ $asignacionesPorDia->flatten()->first()->periodoAcademico->nombre ?? 'N/A' }}
            </p>
        </div>

        @if($asignacionesPorDia->isEmpty())
            <x-alert type="info" title="Sin horarios">
                No tienes horarios asignados para este periodo.
            </x-alert>
        @else
            @php
                $diasSemana = [
                    1 => ['nombre' => 'Lunes', 'color' => 'blue'],
                    2 => ['nombre' => 'Martes', 'color' => 'green'],
                    3 => ['nombre' => 'Miércoles', 'color' => 'purple'],
                    4 => ['nombre' => 'Jueves', 'color' => 'orange'],
                    5 => ['nombre' => 'Viernes', 'color' => 'pink'],
                ];
                
                $colorClasses = [
                    'blue' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-900', 'subtext' => 'text-blue-700', 'time' => 'text-blue-600', 'link' => 'text-blue-600 hover:text-blue-800'],
                    'green' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-900', 'subtext' => 'text-green-700', 'time' => 'text-green-600', 'link' => 'text-green-600 hover:text-green-800'],
                    'purple' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-900', 'subtext' => 'text-purple-700', 'time' => 'text-purple-600', 'link' => 'text-purple-600 hover:text-purple-800'],
                    'orange' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'text' => 'text-orange-900', 'subtext' => 'text-orange-700', 'time' => 'text-orange-600', 'link' => 'text-orange-600 hover:text-orange-800'],
                    'pink' => ['bg' => 'bg-pink-50', 'border' => 'border-pink-200', 'text' => 'text-pink-900', 'subtext' => 'text-pink-700', 'time' => 'text-pink-600', 'link' => 'text-pink-600 hover:text-pink-800'],
                ];
            @endphp

            {{-- Desktop View --}}
            <div class="hidden md:block">
                <x-card padding="none">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach($diasSemana as $dia)
                                        <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-gray-900">
                                            {{ $dia['nombre'] }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    @foreach($diasSemana as $diaNum => $dia)
                                        @php
                                            $cursos = $asignacionesPorDia->get($diaNum, collect());
                                            $colors = $colorClasses[$dia['color']];
                                        @endphp
                                        <td class="px-4 py-4 align-top">
                                            @if($cursos->isEmpty())
                                                <div class="text-center py-8">
                                                    <svg class="w-8 h-8 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                    <p class="text-sm text-gray-400 mt-2">Sin clases</p>
                                                </div>
                                            @else
                                                <div class="space-y-3">
                                                    @foreach($cursos->sortBy('hora_inicio') as $asignacion)
                                                        <div class="{{ $colors['bg'] }} {{ $colors['border'] }} border rounded-lg p-4 hover:shadow-sm transition-shadow">
                                                            <p class="font-semibold {{ $colors['text'] }}">{{ $asignacion->curso->nombre }}</p>
                                                            <p class="text-sm {{ $colors['subtext'] }} mt-1">
                                                                {{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}"
                                                            </p>
                                                            @if($asignacion->hora_inicio && $asignacion->hora_fin)
                                                                <p class="text-xs {{ $colors['time'] }} mt-2 flex items-center">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    {{ \Carbon\Carbon::parse($asignacion->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($asignacion->hora_fin)->format('H:i') }}
                                                                </p>
                                                            @endif
                                                            <a href="{{ route('docente.cursos.show', $asignacion) }}"
                                                               class="text-xs {{ $colors['link'] }} mt-3 inline-block font-medium">
                                                                Ver curso →
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>

            {{-- Mobile View --}}
            <div class="md:hidden space-y-4">
                @foreach($diasSemana as $diaNum => $dia)
                    @php
                        $cursos = $asignacionesPorDia->get($diaNum, collect());
                        $colors = $colorClasses[$dia['color']];
                    @endphp
                    <x-card :title="$dia['nombre']" padding="sm">
                        @if($cursos->isEmpty())
                            <p class="text-sm text-gray-400 text-center py-4">Sin clases</p>
                        @else
                            <div class="space-y-3">
                                @foreach($cursos->sortBy('hora_inicio') as $asignacion)
                                    <div class="{{ $colors['bg'] }} {{ $colors['border'] }} border rounded-lg p-3">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <p class="font-semibold {{ $colors['text'] }}">{{ $asignacion->curso->nombre }}</p>
                                                <p class="text-sm {{ $colors['subtext'] }}">{{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}"</p>
                                                @if($asignacion->hora_inicio && $asignacion->hora_fin)
                                                    <p class="text-xs {{ $colors['time'] }} mt-1">
                                                        {{ \Carbon\Carbon::parse($asignacion->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($asignacion->hora_fin)->format('H:i') }}
                                                    </p>
                                                @endif
                                            </div>
                                            <a href="{{ route('docente.cursos.show', $asignacion) }}"
                                               class="text-xs {{ $colors['link'] }} font-medium">
                                                Ver →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-card>
                @endforeach
            </div>
        @endif
    </div>
</x-docente-layout>
