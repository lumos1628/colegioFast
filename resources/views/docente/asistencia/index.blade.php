<x-portal-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <a href="{{ route('docente.cursos.show', $asignacion) }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Volver a {{ $asignacion->curso->nombre }}
        </a>

        <h1 class="text-3xl font-bold mb-2">Registrar Asistencia</h1>
        <p class="text-gray-600 mb-6">{{ $asignacion->curso->nombre }} - {{ $asignacion->curso->grado }} {{ $asignacion->curso->seccion }}</p>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('docente.cursos.asistencia.index', $asignacion) }}" class="flex gap-3 items-end">
                <div class="flex-1">
                    <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                    <input type="date" name="fecha" id="fecha" value="{{ $fecha }}"
                           class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 transition">
                    Ver fecha
                </button>
            </form>
        </div>

        <form action="{{ route('docente.cursos.asistencia.store', $asignacion) }}" method="POST">
            @csrf
            <input type="hidden" name="fecha" value="{{ $fecha }}">

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-xl font-semibold">Alumnos ({{ $alumnos->count() }})</h2>
                </div>

                @if($alumnos->isEmpty())
                    <div class="p-6 text-gray-500">No hay alumnos matriculados en este curso.</div>
                @else
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumno</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observación</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($alumnos as $index => $alumno)
                                @php
                                    $estadoActual = $asistencias[$alumno->id]->estado->value ?? 'presente';
                                    $observacionActual = $asistencias[$alumno->id]->observacion ?? '';
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <p class="font-medium">{{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</p>
                                        <p class="text-sm text-gray-500">DNI: {{ $alumno->dni }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2" data-alumno-index="{{ $index }}">
                                            <button type="button" data-estado="presente"
                                                    class="estado-btn px-3 py-1 rounded text-sm font-medium transition
                                                    {{ $estadoActual === 'presente' ? 'bg-green-500 text-white' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                                                P
                                            </button>
                                            <button type="button" data-estado="tardanza"
                                                    class="estado-btn px-3 py-1 rounded text-sm font-medium transition
                                                    {{ $estadoActual === 'tardanza' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}">
                                                T
                                            </button>
                                            <button type="button" data-estado="ausente"
                                                    class="estado-btn px-3 py-1 rounded text-sm font-medium transition
                                                    {{ $estadoActual === 'ausente' ? 'bg-red-500 text-white' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                                A
                                            </button>
                                            <button type="button" data-estado="justificado"
                                                    class="estado-btn px-3 py-1 rounded text-sm font-medium transition
                                                    {{ $estadoActual === 'justificado' ? 'bg-blue-500 text-white' : 'bg-blue-100 text-blue-800 hover:bg-blue-200' }}">
                                                J
                                            </button>
                                        </div>
                                        <input type="hidden" name="asistencias[{{ $index }}][estado]" value="{{ $estadoActual }}" class="estado-input">
                                        <input type="hidden" name="asistencias[{{ $index }}][alumno_id]" value="{{ $alumno->id }}">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="asistencias[{{ $index }}][observacion]"
                                               value="{{ $observacionActual }}"
                                               placeholder="Observación opcional"
                                               class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="border-t border-gray-200 px-6 py-4">
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                            Guardar asistencia
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('.estado-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const container = this.closest('[data-alumno-index]');
                const estado = this.dataset.estado;
                const input = container.parentElement.querySelector('.estado-input');

                input.value = estado;

                container.querySelectorAll('.estado-btn').forEach(b => {
                    const btnEstado = b.dataset.estado;
                    b.className = 'estado-btn px-3 py-1 rounded text-sm font-medium transition';

                    if (btnEstado === estado) {
                        b.className += btnEstado === 'presente' ? ' bg-green-500 text-white' :
                                       btnEstado === 'tardanza' ? ' bg-yellow-500 text-white' :
                                       btnEstado === 'ausente' ? ' bg-red-500 text-white' :
                                       ' bg-blue-500 text-white';
                    } else {
                        b.className += btnEstado === 'presente' ? ' bg-green-100 text-green-800 hover:bg-green-200' :
                                       btnEstado === 'tardanza' ? ' bg-yellow-100 text-yellow-800 hover:bg-yellow-200' :
                                       btnEstado === 'ausente' ? ' bg-red-100 text-red-800 hover:bg-red-200' :
                                       ' bg-blue-100 text-blue-800 hover:bg-blue-200';
                    }
                });
            });
        });
    </script>
</x-portal-layout>
