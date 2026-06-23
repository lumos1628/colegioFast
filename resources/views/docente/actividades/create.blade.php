<x-portal-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <a href="{{ route('docente.cursos.actividades.index', $asignacion) }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Volver a actividades
        </a>

        <h1 class="text-3xl font-bold mb-2">Crear Actividad</h1>
        <p class="text-gray-600 mb-6">{{ $asignacion->curso->nombre }} - {{ $asignacion->curso->grado }} {{ $asignacion->curso->seccion }}</p>

        <form action="{{ route('docente.cursos.actividades.store', $asignacion) }}" method="POST" class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            @csrf

            <div class="mb-4">
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">Título de la actividad</label>
                <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
                       class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titulo') border-red-500 @enderror">
                @error('titulo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">Descripción (opcional)</label>
                <textarea name="descripcion" id="descripcion" rows="3"
                          class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}" required
                       class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('fecha') border-red-500 @enderror">
                @error('fecha')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="competencia_id" class="block text-sm font-medium text-gray-700 mb-2">Competencia</label>
                <select name="competencia_id" id="competencia_id" required
                        class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('competencia_id') border-red-500 @enderror">
                    <option value="">Seleccionar competencia</option>
                    @foreach($competencias as $competencia)
                        <option value="{{ $competencia->id }}" {{ old('competencia_id') == $competencia->id ? 'selected' : '' }}>
                            {{ $competencia->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('competencia_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="capacidad_id" class="block text-sm font-medium text-gray-700 mb-2">Capacidad</label>
                <select name="capacidad_id" id="capacidad_id" required
                        class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('capacidad_id') border-red-500 @enderror">
                    <option value="">Seleccionar capacidad</option>
                </select>
                @error('capacidad_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    Crear actividad
                </button>
                <a href="{{ route('docente.cursos.actividades.index', $asignacion) }}"
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 transition">
                    Cancelar
                </a>
            </div>
        </form>
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
</x-portal-layout>
