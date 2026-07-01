<x-docente-layout :docente="$docente" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Mis Cursos', 'url' => route('docente.dashboard')],
            ['label' => $asignacion->curso->nombre, 'url' => route('docente.cursos.show', $asignacion)],
            ['label' => 'Actividades', 'url' => route('docente.cursos.actividades.index', $asignacion)],
            ['label' => $actividad->titulo, 'url' => route('docente.cursos.actividades.show', [$asignacion, $actividad])],
            ['label' => 'Editar']
        ]" />

        <x-card>
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Editar Actividad</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $asignacion->curso->nombre }} - {{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}"
                </p>
            </div>

            <form action="{{ route('docente.cursos.actividades.update', [$asignacion, $actividad]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Título --}}
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                            Título de la actividad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $actividad->titulo) }}" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('titulo') border-red-300 @enderror"
                               placeholder="Ej: Evaluación de fracciones">
                        @error('titulo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('descripcion') border-red-300 @enderror"
                                  placeholder="Describe brevemente la actividad...">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de clase <span class="text-red-500">*</span>
                        </label>

                        @php
                            $fechaActual = old('fecha', $actividad->fecha->format('Y-m-d'));
                            $esSiguienteClase = $siguienteClase && $siguienteClase->format('Y-m-d') === $fechaActual;
                            $esSubsiguienteClase = $subsiguienteClase && $subsiguienteClase->format('Y-m-d') === $fechaActual;
                            $opcionSeleccionada = old('fecha_option', $esSiguienteClase ? 'siguiente' : ($esSubsiguienteClase ? 'subsiguiente' : 'custom'));
                        @endphp

                        <input type="hidden" name="fecha" id="fecha-hidden" value="{{ $fechaActual }}">

                        <div class="space-y-2 mb-3">
                            {{-- Opción 1: Siguiente clase --}}
                            @if($siguienteClase)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors fecha-option">
                                    <input type="radio" name="fecha_option" value="siguiente"
                                           class="fecha-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                           {{ $opcionSeleccionada === 'siguiente' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Siguiente clase</p>
                                        <p class="text-xs text-blue-600">{{ $siguienteClaseFormateada }}</p>
                                    </div>
                                </label>
                            @endif

                            {{-- Opción 2: Subsiguiente clase --}}
                            @if($subsiguienteClase)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors fecha-option">
                                    <input type="radio" name="fecha_option" value="subsiguiente"
                                           class="fecha-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                           {{ $opcionSeleccionada === 'subsiguiente' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Subsiguiente clase</p>
                                        <p class="text-xs text-blue-600">{{ $subsiguienteClaseFormateada }}</p>
                                    </div>
                                </label>
                            @endif

                            {{-- Opción 3: Fecha personalizada --}}
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors fecha-option" id="fecha-custom-label">
                                <input type="radio" name="fecha_option" value="custom" class="fecha-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" id="fecha-custom-radio"
                                       {{ $opcionSeleccionada === 'custom' ? 'checked' : '' }}>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Fecha personalizada</p>
                                    <div id="fecha-custom-container" class="mt-2 {{ $opcionSeleccionada === 'custom' ? '' : 'hidden' }}">
                                        <input type="date" name="fecha_custom" id="fecha-custom-input"
                                               min="{{ $asignacion->periodoAcademico->fecha_inicio->format('Y-m-d') }}"
                                               max="{{ $asignacion->periodoAcademico->fecha_fin->format('Y-m-d') }}"
                                               value="{{ $opcionSeleccionada === 'custom' ? $fechaActual : old('fecha_custom') }}"
                                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                </div>
                            </label>
                        </div>

                        @error('fecha')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('fecha_custom')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Competencia --}}
                    <div>
                        <label for="competencia_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Competencia <span class="text-red-500">*</span>
                        </label>
                        <select name="competencia_id" id="competencia_id" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('competencia_id') border-red-300 @enderror">
                            <option value="">Seleccionar competencia</option>
                            @foreach($competencias as $competencia)
                                <option value="{{ $competencia->id }}" {{ old('competencia_id', $actividad->competencia_id) == $competencia->id ? 'selected' : '' }}>
                                    {{ $competencia->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('competencia_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Capacidad --}}
                    <div>
                        <label for="capacidad_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Capacidad <span class="text-red-500">*</span>
                        </label>
                        <select name="capacidad_id" id="capacidad_id" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('capacidad_id') border-red-300 @enderror">
                            <option value="">Seleccionar capacidad</option>
                        </select>
                        @error('capacidad_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <x-button variant="secondary" :href="route('docente.cursos.actividades.show', [$asignacion, $actividad])">
                        Cancelar
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Actualizar Actividad
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        const competencias = @json($competencias);
        const capacidadSeleccionada = '{{ old('capacidad_id', $actividad->capacidad_id) }}';

        document.getElementById('competencia_id').addEventListener('change', function() {
            const capacidadSelect = document.getElementById('capacidad_id');
            capacidadSelect.innerHTML = '<option value="">Seleccionar capacidad</option>';

            const competenciaId = this.value;
            if (!competenciaId) return;

            const competencia = competencias.find(c => c.id == competenciaId);
            if (competencia && competencia.capacidades) {
                competencia.capacidades.forEach(capacidad => {
                    const option = document.createElement('option');
                    option.value = capacidad.id;
                    option.textContent = capacidad.nombre;
                    if (capacidad.id == capacidadSeleccionada) {
                        option.selected = true;
                    }
                    capacidadSelect.appendChild(option);
                });
            }
        });

        document.getElementById('competencia_id').dispatchEvent(new Event('change'));

        const fechaRadios = document.querySelectorAll('.fecha-radio');
        const fechaCustomRadio = document.getElementById('fecha-custom-radio');
        const fechaCustomContainer = document.getElementById('fecha-custom-container');
        const fechaCustomInput = document.getElementById('fecha-custom-input');
        const fechaOptions = document.querySelectorAll('.fecha-option');
        const fechaHidden = document.getElementById('fecha-hidden');

        const siguienteClase = '{{ $siguienteClase ? $siguienteClase->format("Y-m-d") : "" }}';
        const subsiguienteClase = '{{ $subsiguienteClase ? $subsiguienteClase->format("Y-m-d") : "" }}';

        function updateFechaSelection() {
            fechaOptions.forEach(option => {
                option.classList.remove('border-blue-500', 'bg-blue-50');
                option.classList.add('border-gray-200');
            });

            const selectedRadio = document.querySelector('.fecha-radio:checked');
            if (selectedRadio) {
                const parentLabel = selectedRadio.closest('.fecha-option');
                if (parentLabel) {
                    parentLabel.classList.remove('border-gray-200');
                    parentLabel.classList.add('border-blue-500', 'bg-blue-50');
                }
            }

            if (fechaCustomRadio && fechaCustomRadio.checked) {
                if (fechaCustomContainer) {
                    fechaCustomContainer.classList.remove('hidden');
                }
                if (fechaCustomInput && fechaCustomInput.value) {
                    fechaHidden.value = fechaCustomInput.value;
                }
            } else {
                if (fechaCustomContainer) {
                    fechaCustomContainer.classList.add('hidden');
                }

                if (selectedRadio) {
                    if (selectedRadio.value === 'siguiente') {
                        fechaHidden.value = siguienteClase;
                    } else if (selectedRadio.value === 'subsiguiente') {
                        fechaHidden.value = subsiguienteClase;
                    }
                }
            }
        }

        fechaRadios.forEach(radio => {
            radio.addEventListener('change', updateFechaSelection);
        });

        if (fechaCustomInput) {
            fechaCustomInput.addEventListener('change', function() {
                fechaHidden.value = this.value;
                if (fechaCustomRadio) {
                    fechaCustomRadio.checked = true;
                    updateFechaSelection();
                }
            });
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            if (!fechaHidden.value) {
                e.preventDefault();
                alert('Por favor selecciona una fecha para la actividad.');
                return false;
            }
        });

        updateFechaSelection();
    </script>
</x-docente-layout>
