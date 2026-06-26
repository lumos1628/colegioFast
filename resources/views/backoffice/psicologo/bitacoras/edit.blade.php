<x-psicologo-layout :psicologo="$psicologo" :alumnos-atendidos="$alumnosAtendidos">
    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[['label' => 'Bitácora', 'url' => route('psicologo.bitacoras.index')], ['label' => 'Editar bitácora']]" />

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar Bitácora Psicológica</h1>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('psicologo.bitacoras.update', $bitacora) }}" method="POST">
                @csrf @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alumno</label>
                        <select name="alumno_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm @error('alumno_id') border-red-300 @enderror">
                            @foreach($alumnos as $alumno)
                                <option value="{{ $alumno->id }}" {{ old('alumno_id', $bitacora->alumno_id) == $alumno->id ? 'selected' : '' }}>{{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }} — {{ $alumno->grado }}°{{ $alumno->seccion }}</option>
                            @endforeach
                        </select>
                        @error('alumno_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                        <input type="date" name="fecha" value="{{ old('fecha', $bitacora->fecha->format('Y-m-d')) }}" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm @error('fecha') border-red-300 @enderror">
                        @error('fecha')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea name="observaciones" rows="8" required
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm @error('observaciones') border-red-300 @enderror">{{ old('observaciones', $bitacora->observaciones) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Mínimo 10 caracteres. Esta información es privada y solo accesible por el psicólogo.</p>
                        @error('observaciones')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('psicologo.bitacoras.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-psicologo-layout>
