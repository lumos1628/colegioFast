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
                        <h3 class="text-lg font-semibold text-gray-900">Últimas notificaciones</h3>
                        <p class="text-sm text-gray-500 mt-1">Alertas recientes</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($notificacionesRecientes as $notificacion)
                            <div class="p-4 {{ $notificacion->leido ? 'bg-white' : 'bg-violet-50' }}">
                                <div class="flex items-start">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900 {{ $notificacion->leido ? '' : 'font-medium' }}">
                                            {{ $notificacion->mensaje }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $notificacion->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if(! $notificacion->leido)
                                        <span class="ml-2 w-2 h-2 rounded-full bg-violet-600"></span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Sin notificaciones</p>
                            </div>
                        @endforelse
                    </div>
                    @if($notificacionesRecientes->isNotEmpty())
                        <div class="border-t border-gray-200 px-4 py-3">
                            <a href="{{ route('padre.notificaciones') }}" class="text-sm text-violet-600 hover:text-violet-800 font-medium">
                                Ver todas las notificaciones →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-padre-layout>
