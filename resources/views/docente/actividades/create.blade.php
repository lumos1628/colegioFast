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
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('fecha') border-red-300 @enderror">
                        @error('fecha')
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
    </script>
</x-docente-layout>
