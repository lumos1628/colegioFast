<x-docente-layout :docente="$docente" :cursos-por-dia="$cursosPorDia">
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Mis Cursos', 'url' => route('docente.dashboard')],
            ['label' => $asignacion->curso->nombre, 'url' => route('docente.cursos.show', $asignacion)],
            ['label' => 'Actividades', 'url' => route('docente.cursos.actividades.index', $asignacion)],
            ['label' => 'Nueva Actividad']
        ]" />

        <x-card>
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Nueva Actividad</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $asignacion->curso->nombre }} - {{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}"
                </p>
            </div>

            <form action="{{ route('docente.cursos.actividades.store', $asignacion) }}" method="POST">
                @csrf

                <div class="space-y-6">
                    {{-- Título --}}
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                            Título de la actividad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
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
                                  placeholder="Describe brevemente la actividad...">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de clase <span class="text-red-500">*</span>
                        </label>

                        @if($proximasFechas->isNotEmpty())
                            <div class="space-y-2 mb-3">
                                @foreach($proximasFechas as $index => $fecha)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors fecha-option">
                                        <input type="radio" name="fecha_option" value="{{ $fecha->format('Y-m-d') }}"
                                               class="fecha-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                               {{ old('fecha_option', $index === 0 ? $proximasFechas->first()->format('Y-m-d') : '') === $fecha->format('Y-m-d') ? 'checked' : '' }}>
                                        <input type="hidden" name="fecha" value="{{ $index === 0 ? old('fecha', $proximasFechas->first()->format('Y-m-d')) : old('fecha') }}" class="fecha-input">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $fecha->locale('es')->isoFormat('dddd, D [de] MMMM') }}</p>
                                            @if($index === 0)
                                                <p class="text-xs text-blue-600">Próxima clase</p>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach

                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors fecha-option" id="fecha-custom-label">
                                    <input type="radio" name="fecha_option" value="custom" class="fecha-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" id="fecha-custom-radio">
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Usar fecha específica</p>
                                        <div id="fecha-custom-container" class="mt-2 hidden">
                                            <input type="date" name="fecha_custom" id="fecha-custom-input"
                                                   min="{{ $asignacion->periodoAcademico->fecha_inicio->format('Y-m-d') }}"
                                                   max="{{ $asignacion->periodoAcademico->fecha_fin->format('Y-m-d') }}"
                                                   value="{{ old('fecha_custom') }}"
                                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="fecha_option" value="custom" checked class="fecha-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Seleccionar fecha</p>
                                        <div class="mt-2">
                                            <input type="date" name="fecha_custom" id="fecha-custom-input"
                                                   min="{{ $asignacion->periodoAcademico->fecha_inicio->format('Y-m-d') }}"
                                                   max="{{ $asignacion->periodoAcademico->fecha_fin->format('Y-m-d') }}"
                                                   value="{{ old('fecha_custom', date('Y-m-d')) }}"
                                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endif

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
                                <option value="{{ $competencia->id }}" {{ old('competencia_id') == $competencia->id ? 'selected' : '' }}>
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
                    <x-button variant="secondary" :href="route('docente.cursos.actividades.index', $asignacion)">
                        Cancelar
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Crear Actividad
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        const competencias = @json($competencias);

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
                    capacidadSelect.appendChild(option);
                });
            }
        });

        @if(old('competencia_id'))
            document.getElementById('competencia_id').dispatchEvent(new Event('change'));
            @if(old('capacidad_id'))
                document.getElementById('capacidad_id').value = '{{ old('capacidad_id') }}';
            @endif
        @endif

        const fechaRadios = document.querySelectorAll('.fecha-radio');
        const fechaCustomRadio = document.getElementById('fecha-custom-radio');
        const fechaCustomContainer = document.getElementById('fecha-custom-container');
        const fechaCustomInput = document.getElementById('fecha-custom-input');
        const fechaOptions = document.querySelectorAll('.fecha-option');

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
                fechaCustomContainer.classList.remove('hidden');
            } else if (fechaCustomContainer) {
                fechaCustomContainer.classList.add('hidden');
            }
        }

        fechaRadios.forEach(radio => {
            radio.addEventListener('change', updateFechaSelection);
        });

        if (fechaCustomInput) {
            fechaCustomInput.addEventListener('change', function() {
                if (fechaCustomRadio) {
                    fechaCustomRadio.checked = true;
                    updateFechaSelection();
                }
            });
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedRadio = document.querySelector('.fecha-radio:checked');
            const fechaInputs = document.querySelectorAll('input[name="fecha"]');

            fechaInputs.forEach(input => {
                if (input.type === 'hidden') {
                    input.remove();
                }
            });

            if (selectedRadio) {
                if (selectedRadio.value === 'custom') {
                    if (fechaCustomInput && fechaCustomInput.value) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'fecha';
                        hiddenInput.value = fechaCustomInput.value;
                        this.appendChild(hiddenInput);
                    }
                } else {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'fecha';
                    hiddenInput.value = selectedRadio.value;
                    this.appendChild(hiddenInput);
                }
            }
        });

        updateFechaSelection();
    </script>
</x-docente-layout>
