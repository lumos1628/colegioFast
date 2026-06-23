<x-portal-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <a href="{{ route('docente.cursos.show', $asignacion) }}"
           class="text-blue-600 hover:underline mb-4 inline-block">
            ← Volver a {{ $asignacion->curso->nombre }}
        </a>

        <h1 class="text-3xl font-bold mb-2">
            {{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}
        </h1>
        <p class="text-gray-600 mb-6">
            DNI: {{ $alumno->dni }} | Grado: {{ $alumno->grado }} | Sección: {{ $alumno->seccion }}
        </p>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold">Calificaciones ({{ $notas->count() }})</h2>
            </div>
            @if($notas->isEmpty())
                <div class="p-6 text-gray-500">Sin calificaciones registradas.</div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($notas as $nota)
                        <li class="px-6 py-4">
                            <p class="font-medium">{{ $nota->actividad->titulo }}</p>
                            <p class="text-sm text-gray-500">
                                Competencia: {{ $nota->actividad->competencia->nombre }} |
                                Calificación: {{ $nota->calificacion->label() }}
                            </p>
                            @if($nota->observacion)
                                <p class="text-sm italic mt-1">{{ $nota->observacion }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold">Asistencias ({{ $asistencias->count() }})</h2>
            </div>
            @if($asistencias->isEmpty())
                <div class="p-6 text-gray-500">Sin registros de asistencia.</div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($asistencias as $asistencia)
                        <li class="px-6 py-4 flex justify-between items-center">
                            <span>{{ $asistencia->fecha->format('d/m/Y') }}</span>
                            <span class="px-3 py-1 rounded text-sm {{ $asistencia->estado->color() }}">
                                {{ $asistencia->estado->label() }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold">Incidencias de Conducta ({{ $incidencias->count() }})</h2>
            </div>
            @if($incidencias->isEmpty())
                <div class="p-6 text-gray-500">Sin incidencias registradas.</div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($incidencias as $incidencia)
                        <li class="px-6 py-4">
                            <p class="font-medium">{{ $incidencia->tipo->label() }}</p>
                            <p class="text-sm text-gray-600">{{ $incidencia->descripcion }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $incidencia->fecha->format('d/m/Y') }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-portal-layout>
