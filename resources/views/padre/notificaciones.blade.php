<x-padre-layout :padre="$padre" :hijos="$hijos">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[
            ['label' => 'Panel', 'url' => route('padre.dashboard')],
            ['label' => 'Notificaciones']
        ]" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Notificaciones</h1>
            <p class="mt-1 text-sm text-gray-500">Alertas y avisos del sistema</p>
        </div>

        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('padre.notificaciones', ['filtro' => 'todas']) }}"
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filtro === 'todas' ? 'bg-violet-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                Todas
            </a>
            <a href="{{ route('padre.notificaciones', ['filtro' => 'no_leidas']) }}"
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filtro === 'no_leidas' ? 'bg-violet-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                No leídas
            </a>
        </div>

        @if(session('success'))
            <x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            @forelse($notificaciones as $notificacion)
                <div class="p-4 border-b border-gray-200 last:border-b-0 {{ $notificacion->leido ? 'bg-white' : 'bg-violet-50' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start flex-1">
                            <div class="mr-3 mt-0.5">
                                @php
                                    $icono = match($notificacion->tipo->value) {
                                        'nota_critica' => 'text-blue-500',
                                        'inasistencia' => 'text-red-500',
                                        'incidencia_conducta' => 'text-yellow-500',
                                        'tarea_pendiente' => 'text-purple-500',
                                        default => 'text-gray-500',
                                    };
                                @endphp
                                <svg class="w-5 h-5 {{ $icono }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900 {{ $notificacion->leido ? '' : 'font-medium' }}">
                                    {{ $notificacion->mensaje }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $notificacion->created_at->format('d/m/Y H:i') }}
                                    <span class="ml-2 text-gray-400">{{ $notificacion->created_at->diffForHumans() }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 ml-4">
                            @if(! $notificacion->leido)
                                <span class="w-2 h-2 rounded-full bg-violet-600"></span>
                                <form action="{{ route('padre.notificaciones.leida', $notificacion) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-violet-600 hover:text-violet-800 font-medium">
                                        Marcar leída
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">Leída</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">
                        @if($filtro === 'no_leidas')
                            No tienes notificaciones sin leer
                        @else
                            No tienes notificaciones
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        @if($notificaciones->hasPages())
            <div class="mt-6">
                {{ $notificaciones->links() }}
            </div>
        @endif
    </div>
</x-padre-layout>
