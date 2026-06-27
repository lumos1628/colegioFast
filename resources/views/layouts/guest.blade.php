<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes gradient-shift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .animate-gradient {
                background-size: 200% 200%;
                animation: gradient-shift 8s ease infinite;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">
            {{-- Panel Visual Izquierdo --}}
            <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 animate-gradient bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-900 dark:via-purple-900 dark:to-pink-900 text-white relative overflow-hidden">
                {{-- Patrón decorativo de fondo --}}
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                    </svg>
                </div>

                {{-- Contenido superior --}}
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-8">
                        <x-application-logo class="w-16 h-16 text-white" />
                        <div>
                            <h1 class="text-3xl font-bold">colegioFast</h1>
                            <p class="text-indigo-200 text-sm">Sistema de Gestión Académica</p>
                        </div>
                    </div>
                </div>

                {{-- Contenido central --}}
                <div class="relative z-10 flex-1 flex flex-col justify-center">
                    <div class="mb-8">
                        <svg class="w-32 h-32 mx-auto mb-6 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h2 class="text-4xl font-bold text-center mb-4">
                            Gestión escolar simplificada
                        </h2>
                        <p class="text-xl text-center text-indigo-100 leading-relaxed">
                            Controla notas, asistencias y comunicación con padres de familia desde una sola plataforma
                        </p>
                    </div>

                    {{-- Features --}}
                    <div class="grid grid-cols-2 gap-4 mt-12">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="font-semibold mb-1">Registro de Notas</h3>
                            <p class="text-sm text-indigo-100">Seguimiento por competencias CNEB</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="font-semibold mb-1">Control de Asistencia</h3>
                            <p class="text-sm text-indigo-100">Registro diario automatizado</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="font-semibold mb-1">Portal para Padres</h3>
                            <p class="text-sm text-indigo-100">Acceso en tiempo real al progreso</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="font-semibold mb-1">Reportes Automáticos</h3>
                            <p class="text-sm text-indigo-100">Exportación a Excel Minedu</p>
                        </div>
                    </div>
                </div>

                {{-- Contenido inferior --}}
                <div class="relative z-10 text-center">
                    <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 border border-white/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span class="text-sm font-medium">Educación Primaria - Perú</span>
                    </div>
                    <p class="text-xs text-indigo-200 mt-4">
                        Alineado al Currículo Nacional de Educación Básica (CNEB)
                    </p>
                </div>
            </div>

            {{-- Panel Formulario Derecho --}}
            <div class="w-full lg:w-1/2 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4 bg-gray-50 dark:bg-gray-900">
                {{-- Logo mobile --}}
                <div class="lg:hidden mb-6 sm:mb-8 transition-all duration-500 hover:scale-105">
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-indigo-600 dark:text-indigo-400 drop-shadow-lg" />
                    </a>
                </div>

                <div class="w-full sm:max-w-md mt-2 px-8 py-7 bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl transition-all duration-300">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                    {{ config('app.name', 'Sistema Escolar') }} &copy; {{ date('Y') }}
                </p>
            </div>
        </div>
    </body>
</html>
