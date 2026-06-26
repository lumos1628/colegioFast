@props(['padre', 'hijos'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'colegioFast') }} - Portal Padre</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar" class="flex flex-col w-72 bg-white border-r border-gray-200 fixed md:relative h-full z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-violet-600 to-violet-700 flex items-center justify-center text-white font-bold text-lg shrink-0">
                        {{ substr($padre->nombres, 0, 1) }}{{ substr($padre->apellido_paterno, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $padre->nombres }} {{ $padre->apellido_paterno }}</h3>
                        <p class="text-sm text-gray-500 truncate">DNI: {{ $padre->dni }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">{{ $padre->user->email }}</span>
                    </div>
                    @if($padre->telefono)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>{{ $padre->telefono }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div class="py-4">
                    <div class="px-4 mb-4">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-2">
                            Mis Hijos
                        </h4>
                        <div class="space-y-1">
                            @foreach($hijos as $hijo)
                                <a href="{{ route('padre.hijos.show', $hijo) }}"
                                   class="block px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('padre.hijos.show') && request()->route('padre.hijos.show') == $hijo->id ? 'bg-violet-50 text-violet-700 border-l-4 border-violet-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                    <p class="font-medium">{{ $hijo->nombres }} {{ $hijo->apellido_paterno }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $hijo->grado }}° "{{ $hijo->seccion }}"
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 p-4 space-y-3">
                <a href="{{ route('padre.notificaciones') }}" class="block text-sm text-gray-600 hover:text-violet-600 transition-colors">
                    Notificaciones
                </a>
                <a href="{{ route('padre.pagos') }}" class="block text-sm text-gray-600 hover:text-violet-600 transition-colors">
                    Estado financiero
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="md:hidden flex items-center justify-between h-16 px-4 bg-white border-b border-gray-200 sticky top-0 z-20">
                <button id="toggle-sidebar" onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-violet-600 to-violet-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900">colegioFast</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-red-600 transition-colors" title="Cerrar sesión">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </header>

            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>
