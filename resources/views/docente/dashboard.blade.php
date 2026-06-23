<x-portal-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-2">Mis Cursos</h1>
        <p class="text-gray-600 mb-6">{{ auth()->user()->name }}</p>

        @if($asignaciones->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                <p class="text-yellow-800">No tienes cursos asignados en el periodo activo.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($asignaciones as $asignacion)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-semibold mb-2">{{ $asignacion->curso->nombre }}</h2>
                        <p class="text-gray-600 mb-1">
                            {{ $asignacion->curso->grado }} - {{ $asignacion->curso->seccion }}
                        </p>
                        <p class="text-sm text-gray-500 mb-4">
                            Periodo: {{ $asignacion->periodoAcademico->nombre }}
                        </p>
                        <a href="{{ route('docente.cursos.show', $asignacion) }}"
                           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            Ver alumnos
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-portal-layout>
