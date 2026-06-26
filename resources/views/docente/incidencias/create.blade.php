<x-docente-layout :docente="$docente" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Mis Cursos', 'url' => route('docente.dashboard')],
            ['label' => $asignacion->curso->nombre, 'url' => route('docente.cursos.show', $asignacion)],
            ['label' => $alumno->nombres . ' ' . $alumno->apellido_paterno, 'url' => route('docente.cursos.alumnos.show', [$asignacion, $alumno])],
            ['label' => 'Nueva Incidencia']
        ]" />

        <x-card>
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Registrar Incidencia de Conducta</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}
                </p>
            </div>

            <form action="{{ route('docente.cursos.alumnos.incidencias.store', [$asignacion, $alumno]) }}" method="POST">
                @csrf

                <div class="space-y-6">
                    {{-- Tipo de incidencia --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Tipo de incidencia <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all incidencia-option {{ old('tipo') === 'falta_leve' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="tipo" value="falta_leve"
                                       class="sr-only incidencia-radio"
                                       {{ old('tipo') === 'falta_leve' ? 'checked' : '' }}>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900">Falta leve</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all incidencia-option {{ old('tipo') === 'falta_grave' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="tipo" value="falta_grave"
                                       class="sr-only incidencia-radio"
                                       {{ old('tipo') === 'falta_grave' ? 'checked' : '' }}>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900">Falta grave</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all incidencia-option {{ old('tipo') === 'merito' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="tipo" value="merito"
                                       class="sr-only incidencia-radio"
                                       {{ old('tipo') === 'merito' ? 'checked' : '' }}>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900">Mérito</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('tipo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción <span class="text-red-500">*</span>
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="4" required
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('descripcion') border-red-300 @enderror"
                                  placeholder="Describe la incidencia con detalle...">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('fecha') border-red-300 @enderror">
                        @error('fecha')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <x-button variant="secondary" :href="route('docente.cursos.alumnos.show', [$asignacion, $alumno])">
                        Cancelar
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Registrar Incidencia
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        const incidenciaRadios = document.querySelectorAll('.incidencia-radio');
        const incidenciaOptions = document.querySelectorAll('.incidencia-option');

        function updateIncidenciaSelection() {
            incidenciaOptions.forEach(option => {
                option.classList.remove('border-yellow-500', 'bg-yellow-50', 'border-red-500', 'bg-red-50', 'border-green-500', 'bg-green-50');
                option.classList.add('border-gray-200');
            });

            const selectedRadio = document.querySelector('.incidencia-radio:checked');
            if (selectedRadio) {
                const parentLabel = selectedRadio.closest('.incidencia-option');
                if (parentLabel) {
                    parentLabel.classList.remove('border-gray-200');
                    const value = selectedRadio.value;
                    if (value === 'falta_leve') {
                        parentLabel.classList.add('border-yellow-500', 'bg-yellow-50');
                    } else if (value === 'falta_grave') {
                        parentLabel.classList.add('border-red-500', 'bg-red-50');
                    } else if (value === 'merito') {
                        parentLabel.classList.add('border-green-500', 'bg-green-50');
                    }
                }
            }
        }

        incidenciaRadios.forEach(radio => {
            radio.addEventListener('change', updateIncidenciaSelection);
        });

        updateIncidenciaSelection();
    </script>
</x-docente-layout>
