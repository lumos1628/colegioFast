<x-administrativo-layout>
    <h1>Hola Secretaria</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
</x-administrativo-layout>
