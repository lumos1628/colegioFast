<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav>
        <a href="{{ route('docente.dashboard') }}">Docente</a> |
        <a href="{{ route('alumno') }}">Alumno</a> |
        <a href="{{ route('padre') }}">Padre</a> |
        <a href="{{ route('psicologo') }}">Psicólogo</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Cerrar sesión</button>
        </form>
    </nav>
    <hr>
    <main>
        {{ $slot }}
    </main>
</body>
</html>
