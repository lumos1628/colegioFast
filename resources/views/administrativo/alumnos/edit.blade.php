<x-administrativo-layout>
    <div class="max-w-2xl mx-auto">
        <x-breadcrumb :items="[
            ['label' => 'Alumnos', 'url' => route('admin.alumnos.index')],
            ['label' => 'Editar: ' . $alumno->nombres . ' ' . $alumno->apellido_paterno]
        ]" />

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar Alumno</h1>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.alumnos.update', $alumno) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombres</label>
                            <input type="text" name="nombres" value="{{ old('nombres', $alumno->nombres) }}" required
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('nombres') border-red-300 @enderror">
                            @error('nombres')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno', $alumno->apellido_paterno) }}" required
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('apellido_paterno') border-red-300 @enderror">
                            @error('apellido_paterno')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido Materno</label>
                            <input type="text" name="apellido_materno" value="{{ old('apellido_materno', $alumno->apellido_materno) }}" required
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('apellido_materno') border-red-300 @enderror">
                            @error('apellido_materno')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $alumno->fecha_nacimiento->format('Y-m-d')) }}" required
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('fecha_nacimiento') border-red-300 @enderror">
                            @error('fecha_nacimiento')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">DNI</label>
                            <input type="text" name="dni" value="{{ old('dni', $alumno->dni) }}" maxlength="8" required
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('dni') border-red-300 @enderror">
                            @error('dni')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Grado</label>
                            <select name="grado" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('grado') border-red-300 @enderror">
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ old('grado', $alumno->grado) == $i ? 'selected' : '' }}>{{ $i }}°</option>
                                @endfor
                            </select>
                            @error('grado')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sección</label>
                            <input type="text" name="seccion" value="{{ old('seccion', $alumno->seccion) }}" maxlength="1" required
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm uppercase @error('seccion') border-red-300 @enderror">
                            @error('seccion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.alumnos.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-administrativo-layout>
