<x-portal-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <a href="{{ route('docente.dashboard') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Volver a mis cursos
        </a>

        <h1 class="text-3xl font-bold mb-2">{{ $asignacion->curso->nombre }}</h1>
        <p class="text-gray-600 mb-2">
            {{ $asignacion->curso->grado }} - {{ $asignacion->curso->seccion }}
        </p>
        <p class="text-sm text-gray-500 mb-4">
            Periodo: {{ $asignacion->periodoAcademico->nombre }}
        </p>

        <div class="flex gap-3 mb-6">
            <a href="{{ route('docente.cursos.actividades.index', $asignacion) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Gestionar actividades
            </a>
            <a href="{{ route('docente.cursos.asistencia.index', $asignacion) }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Registrar asistencia
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold mb-3">
                    Alumnos Matriculados ({{ $asignacion->matriculas->count() }})
                </h2>
                <input type="text" id="buscar-alumno" placeholder="Buscar por nombre o DNI..."
                       class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            @if($asignacion->matriculas->isEmpty())
                <div class="p-6">
                    <p class="text-gray-500">No hay alumnos matriculados en este curso.</p>
                </div>
            @else
                <ul class="divide-y divide-gray-200" id="lista-alumnos">
                    @foreach($asignacion->matriculas as $matricula)
                        <li class="px-6 py-4 hover:bg-gray-50 alumno-item"
                            data-nombre="{{ strtolower($matricula->alumno->nombres . ' ' . $matricula->alumno->apellido_paterno . ' ' . $matricula->alumno->apellido_materno) }}"
                            data-dni="{{ $matricula->alumno->dni }}">
                            <a href="{{ route('docente.cursos.alumnos.show', [$asignacion, $matricula->alumno]) }}"
                               class="block">
                                <p class="font-medium">
                                    {{ $matricula->alumno->nombres }}
                                    {{ $matricula->alumno->apellido_paterno }}
                                    {{ $matricula->alumno->apellido_materno }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    DNI: {{ $matricula->alumno->dni }} |
                                    Grado: {{ $matricula->alumno->grado }}
                                </p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('buscar-alumno').addEventListener('input', function(e) {
            const filtro = e.target.value.toLowerCase();
            document.querySelectorAll('.alumno-item').forEach(item => {
                const nombre = item.dataset.nombre;
                const dni = item.dataset.dni;
                item.style.display = (nombre.includes(filtro) || dni.includes(filtro)) ? '' : 'none';
            });
        });
    </script>
</x-portal-layout>
