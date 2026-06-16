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
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4 animate-gradient bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 dark:from-gray-950 dark:via-indigo-950 dark:to-purple-950">
            <div class="mb-6 sm:mb-8 transition-all duration-500 hover:scale-105">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-indigo-600 dark:text-indigo-400 drop-shadow-lg" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-7 bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl shadow-2xl shadow-indigo-500/10 dark:shadow-indigo-500/5 border border-white/50 dark:border-gray-700/50 overflow-hidden sm:rounded-2xl transition-all duration-300 hover:shadow-indigo-500/15">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-indigo-400/60 dark:text-indigo-500/40">
                {{ config('app.name', 'Sistema Escolar') }} &copy; {{ date('Y') }}
            </p>
        </div>
    </body>
</html>
