<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Administrativo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between h-14 gap-3">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900">colegioFast</span>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('admin') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Dashboard</a>
                    <a href="{{ route('admin.alumnos.index') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.alumnos.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Alumnos</a>
                    <a href="{{ route('admin.padres.index') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.padres.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Padres</a>
                    <a href="{{ route('admin.matriculas.index') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.matriculas.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Matrículas</a>
                    <a href="{{ route('admin.periodos.index') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.periodos.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Periodos</a>
                    <a href="{{ route('admin.cursos.index') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.cursos.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Cursos</a>
                    <a href="{{ route('admin.asignaciones.index') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.asignaciones.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Asignaciones</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="ml-2 px-3 py-1.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 border border-red-200 transition-colors">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</body>
</html>
