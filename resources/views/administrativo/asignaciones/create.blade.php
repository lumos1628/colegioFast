<x-administrativo-layout>
    <div class="max-w-2xl mx-auto">
        <x-breadcrumb :items="[['label' => 'Asignaciones', 'url' => route('admin.asignaciones.index')], ['label' => 'Nueva asignación']]" />
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Nueva Asignación</h1>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.asignaciones.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Docente</label>
                        <select name="docente_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('docente_id') border-red-300 @enderror">
                            <option value="">Seleccionar docente</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id }}" {{ old('docente_id') == $docente->id ? 'selected' : '' }}>{{ $docente->user->name }} — {{ $docente->especialidad }}</option>
                            @endforeach
                        </select>
                        @error('docente_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                        <select name="curso_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('curso_id') border-red-300 @enderror">
                            <option value="">Seleccionar curso</option>
                            @foreach($cursos as $curso)
                                <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>{{ $curso->nombre }} — {{ $curso->grado }}° "{{ $curso->seccion }}" ({{ $curso->area_curricular }})</option>
                            @endforeach
                        </select>
                        @error('curso_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Periodo Académico</label>
                        <select name="periodo_academico_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('periodo_academico_id') border-red-300 @enderror">
                            <option value="">Seleccionar periodo</option>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->id }}" {{ old('periodo_academico_id') == $periodo->id ? 'selected' : '' }}>{{ $periodo->nombre }} ({{ $periodo->anio_escolar }}) {{ $periodo->activo ? '— ACTIVO' : '' }}</option>
                            @endforeach
                        </select>
                        @error('periodo_academico_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Horario (opcional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Día</label>
                                <select name="dia_semana" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Sin día</option>
                                    <option value="1" {{ old('dia_semana') == 1 ? 'selected' : '' }}>Lunes</option>
                                    <option value="2" {{ old('dia_semana') == 2 ? 'selected' : '' }}>Martes</option>
                                    <option value="3" {{ old('dia_semana') == 3 ? 'selected' : '' }}>Miércoles</option>
                                    <option value="4" {{ old('dia_semana') == 4 ? 'selected' : '' }}>Jueves</option>
                                    <option value="5" {{ old('dia_semana') == 5 ? 'selected' : '' }}>Viernes</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hora inicio</label>
                                <input type="time" name="hora_inicio" value="{{ old('hora_inicio') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hora fin</label>
                                <input type="time" name="hora_fin" value="{{ old('hora_fin') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.asignaciones.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Crear asignación</button>
                </div>
            </form>
        </div>
    </div>
</x-administrativo-layout>
