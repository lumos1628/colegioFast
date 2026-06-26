<x-administrativo-layout>
    <div class="max-w-2xl mx-auto">
        <x-breadcrumb :items="[['label' => 'Asignaciones', 'url' => route('admin.asignaciones.index')], ['label' => 'Editar']]" />
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar Asignación</h1>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.asignaciones.update', $asignacion) }}" method="POST">
                @csrf @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Docente</label>
                        <select name="docente_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id }}" {{ old('docente_id', $asignacion->docente_id) == $docente->id ? 'selected' : '' }}>{{ $docente->user->name }} — {{ $docente->especialidad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                        <select name="curso_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            @foreach($cursos as $curso)
                                <option value="{{ $curso->id }}" {{ old('curso_id', $asignacion->curso_id) == $curso->id ? 'selected' : '' }}>{{ $curso->nombre }} — {{ $curso->grado }}° "{{ $curso->seccion }}"</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periodo Académico</label>
                        <select name="periodo_academico_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->id }}" {{ old('periodo_academico_id', $asignacion->periodo_academico_id) == $periodo->id ? 'selected' : '' }}>{{ $periodo->nombre }} ({{ $periodo->anio_escolar }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Horario</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Día</label>
                                <select name="dia_semana" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Sin día</option>
                                    @php $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes']; @endphp
                                    @foreach($dias as $num => $nombre)
                                        <option value="{{ $num }}" {{ old('dia_semana', $asignacion->dia_semana) == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hora inicio</label>
                                <input type="time" name="hora_inicio" value="{{ old('hora_inicio', $asignacion->hora_inicio) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hora fin</label>
                                <input type="time" name="hora_fin" value="{{ old('hora_fin', $asignacion->hora_fin) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.asignaciones.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-administrativo-layout>
