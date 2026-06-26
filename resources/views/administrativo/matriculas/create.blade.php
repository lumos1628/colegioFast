<x-administrativo-layout>
    <div class="max-w-2xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Matricular Alumno</h1>
            <p class="mt-1 text-sm text-gray-500">Periodo activo: {{ $periodoActivo->nombre }}</p>
        </div>

        {{-- Content --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.matriculas.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    {{-- Alumno --}}
                    <div>
                        <label for="alumno_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Alumno <span class="text-red-500">*</span>
                        </label>
                        @if($alumnos->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
                                No hay alumnos disponibles para matricular. Todos los alumnos ya están matriculados en el periodo activo.
                            </div>
                        @else
                            <select name="alumno_id" id="alumno_id" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('alumno_id') border-red-300 @enderror">
                                <option value="">Seleccionar alumno</option>
                                @foreach($alumnos as $alumno)
                                    <option value="{{ $alumno->id }}" {{ old('alumno_id') == $alumno->id ? 'selected' : '' }}>
                                        {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}, {{ $alumno->nombres }} - DNI: {{ $alumno->dni }}
                                    </option>
                                @endforeach
                            </select>
                            @error('alumno_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    {{-- Grado --}}
                    <div>
                        <label for="grado" class="block text-sm font-medium text-gray-700 mb-2">
                            Grado <span class="text-red-500">*</span>
                        </label>
                        <select name="grado" id="grado" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('grado') border-red-300 @enderror">
                            <option value="">Seleccionar grado</option>
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ old('grado') == $i ? 'selected' : '' }}>
                                    {{ $i }}° grado
                                </option>
                            @endfor
                        </select>
                        @error('grado')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sección --}}
                    <div>
                        <label for="seccion" class="block text-sm font-medium text-gray-700 mb-2">
                            Sección <span class="text-red-500">*</span>
                        </label>
                        <select name="seccion" id="seccion" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('seccion') border-red-300 @enderror">
                            <option value="">Seleccionar sección</option>
                            @foreach(['A', 'B', 'C', 'D'] as $seccion)
                                <option value="{{ $seccion }}" {{ old('seccion') == $seccion ? 'selected' : '' }}>
                                    "{{ $seccion }}"
                                </option>
                            @endforeach
                        </select>
                        @error('seccion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-600 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Información importante</p>
                                <p>El alumno será matriculado automáticamente en todos los cursos del grado y sección seleccionados para el periodo activo.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.matriculas.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </a>
                    @if($alumnos->isNotEmpty())
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Matricular
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-administrativo-layout>
