<x-portal-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <a href="{{ route('docente.cursos.show', $asignacion) }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Volver a {{ $asignacion->curso->nombre }}
        </a>

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">{{ $asignacion->curso->nombre }}</h1>
                <p class="text-gray-600">{{ $asignacion->curso->grado }} - {{ $asignacion->curso->seccion }}</p>
            </div>
            <a href="{{ route('docente.cursos.actividades.create', $asignacion) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Crear actividad
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($actividades->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                <p class="text-yellow-800">No hay actividades registradas. Crea la primera.</p>
            </div>
        @else
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actividad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Competencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notas</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($actividades as $actividad)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-medium">{{ $actividad->titulo }}</p>
                                    <p class="text-sm text-gray-500">{{ $actividad->capacidad->nombre }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $actividad->competencia->nombre }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $actividad->fecha->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $actividad->notas->count() }} registradas
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('docente.cursos.actividades.show', [$asignacion, $actividad]) }}"
                                       class="text-blue-600 hover:underline text-sm">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-portal-layout>
