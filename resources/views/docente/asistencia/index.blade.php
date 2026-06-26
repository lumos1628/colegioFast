<x-docente-layout :docente="$docente" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Mis Cursos', 'url' => route('docente.dashboard')],
            ['label' => $asignacion->curso->nombre, 'url' => route('docente.cursos.show', $asignacion)],
            ['label' => 'Asistencia']
        ]" />

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Registrar Asistencia</h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $asignacion->curso->nombre }} - {{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}"
            </p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <x-alert type="success" class="mb-6" dismissible>
                {{ session('success') }}
            </x-alert>
        @endif

        {{-- Date Selector --}}
        <x-card class="mb-6">
            <form method="GET" action="{{ route('docente.cursos.asistencia.index', $asignacion) }}" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4">
                <div class="flex-1 max-w-xs">
                    <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                    <input type="date" name="fecha" id="fecha" value="{{ $fecha }}"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                <x-button variant="secondary" type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Ver fecha
                </x-button>
            </form>
        </x-card>

        {{-- Attendance Form --}}
        <form action="{{ route('docente.cursos.asistencia.store', $asignacion) }}" method="POST">
            @csrf
            <input type="hidden" name="fecha" value="{{ $fecha }}">

            <x-card title="Alumnos" :subtitle="$alumnos->count() . ' estudiantes'">
                @if($alumnos->isEmpty())
                    <x-alert type="info">
                        No hay alumnos matriculados en este curso.
                    </x-alert>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alumno
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Observación
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($alumnos as $index => $alumno)
                                    @php
                                        $estadoActual = $asistencias[$alumno->id]->estado->value ?? 'presente';
                                        $observacionActual = $asistencias[$alumno->id]->observacion ?? '';
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-xs">
                                                    {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellido_paterno, 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $alumno->nombres }} {{ $alumno->apellido_paterno }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">DNI: {{ $alumno->dni }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1.5" data-alumno-index="{{ $index }}">
                                                <button type="button" data-estado="presente"
                                                        class="estado-btn px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                                                        {{ $estadoActual === 'presente' ? 'bg-green-500 text-white shadow-sm' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                                    Presente
                                                </button>
                                                <button type="button" data-estado="tardanza"
                                                        class="estado-btn px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                                                        {{ $estadoActual === 'tardanza' ? 'bg-yellow-500 text-white shadow-sm' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }}">
                                                    Tardanza
                                                </button>
                                                <button type="button" data-estado="ausente"
                                                        class="estado-btn px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                                                        {{ $estadoActual === 'ausente' ? 'bg-red-500 text-white shadow-sm' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                                    Ausente
                                                </button>
                                                <button type="button" data-estado="justificado"
                                                        class="estado-btn px-3 py-1.5 rounded-lg text-xs font-medium transition-all
                                                        {{ $estadoActual === 'justificado' ? 'bg-blue-500 text-white shadow-sm' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }}">
                                                    Justificado
                                                </button>
                                            </div>
                                            <input type="hidden" name="asistencias[{{ $index }}][estado]" value="{{ $estadoActual }}" class="estado-input">
                                            <input type="hidden" name="asistencias[{{ $index }}][alumno_id]" value="{{ $alumno->id }}">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="asistencias[{{ $index }}][observacion]"
                                                   value="{{ $observacionActual }}"
                                                   placeholder="Observación opcional"
                                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <x-button variant="success" type="submit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Guardar Asistencia
                        </x-button>
                    </div>
                @endif
            </x-card>
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
                    const baseClasses = 'estado-btn px-3 py-1.5 rounded-lg text-xs font-medium transition-all';
                    
                    if (btnEstado === estado) {
                        b.className = baseClasses + ' ' + getSelectedClasses(btnEstado);
                    } else {
                        b.className = baseClasses + ' ' + getUnselectedClasses(btnEstado);
                    }
                });
            });
        });

        function getSelectedClasses(estado) {
            const classes = {
                'presente': 'bg-green-500 text-white shadow-sm',
                'tardanza': 'bg-yellow-500 text-white shadow-sm',
                'ausente': 'bg-red-500 text-white shadow-sm',
                'justificado': 'bg-blue-500 text-white shadow-sm'
            };
            return classes[estado] || '';
        }

        function getUnselectedClasses(estado) {
            const classes = {
                'presente': 'bg-green-100 text-green-700 hover:bg-green-200',
                'tardanza': 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200',
                'ausente': 'bg-red-100 text-red-700 hover:bg-red-200',
                'justificado': 'bg-blue-100 text-blue-700 hover:bg-blue-200'
            };
            return classes[estado] || '';
        }
    </script>
</x-docente-layout>
