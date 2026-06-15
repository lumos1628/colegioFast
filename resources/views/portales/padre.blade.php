<x-portal-layout>
    <h1>Hola Padre</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
</x-portal-layout>
