@props(['psicologo', 'alumnosAtendidos'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'colegioFast') }} - Portal Psicólogo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar" class="flex flex-col w-72 bg-white border-r border-gray-200 fixed md:relative h-full z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-teal-600 to-teal-700 flex items-center justify-center text-white font-bold text-lg shrink-0">
                        {{ substr($psicologo->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $psicologo->name }}</h3>
                        <p class="text-sm text-gray-500 truncate">Psicólogo</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">{{ $psicologo->email }}</span>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div class="py-4">
                    <div class="px-4 mb-4">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-2">
                            Alumnos Atendidos
                        </h4>
                        <div class="space-y-1">
                            @foreach($alumnosAtendidos as $alumno)
                                <a href="{{ route('psicologo.bitacoras.index', ['alumno' => $alumno->id]) }}"
                                   class="block px-3 py-2 rounded-lg text-sm transition-colors {{ request()->input('alumno') == $alumno->id ? 'bg-teal-50 text-teal-700 border-l-4 border-teal-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                    <p class="font-medium">{{ $alumno->nombres }} {{ $alumno->apellido_paterno }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $alumno->grado }}° "{{ $alumno->seccion }}"</p>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @if($alumnosAtendidos->isEmpty())
                        <div class="px-4 py-8 text-center text-gray-500 text-sm">
                            Sin alumnos atendidos
                        </div>
                    @endif
                </div>
            </div>

            <div class="border-t border-gray-200 p-4 space-y-3">
                <a href="{{ route('psicologo.dashboard') }}" class="block text-sm text-gray-600 hover:text-teal-600 transition-colors">
                    Dashboard
                </a>
                <a href="{{ route('psicologo.bitacoras.index') }}" class="block text-sm text-gray-600 hover:text-teal-600 transition-colors">
                    Todas las bitácoras
                </a>
                <a href="{{ route('psicologo.bitacoras.create') }}" class="block text-sm text-teal-600 hover:text-teal-800 font-medium transition-colors">
                    + Nueva bitácora
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
                    <div class="w-8 h-8 bg-gradient-to-br from-teal-600 to-teal-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
