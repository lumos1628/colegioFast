<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Administrativo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav>
        <a href="{{ route('admin') }}">Admin</a> |
        <a href="{{ route('director') }}">Director</a> |
        <a href="{{ route('secretaria') }}">Secretaria</a>
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
