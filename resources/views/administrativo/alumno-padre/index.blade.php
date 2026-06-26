<x-administrativo-layout>
    <div class="max-w-4xl mx-auto">
        <x-breadcrumb :items="[
            ['label' => 'Alumnos', 'url' => route('admin.alumnos.index')],
            ['label' => $alumno->nombres . ' ' . $alumno->apellido_paterno . ' — Padres']
        ]" />

        <div class="flex items-center mb-6">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellido_paterno, 0, 1) }}
            </div>
            <div class="ml-4">
                <h1 class="text-2xl font-bold text-gray-900">{{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</h1>
                <p class="text-sm text-gray-500">{{ $alumno->grado }}° "{{ $alumno->seccion }}" — DNI: {{ $alumno->dni }}</p>
            </div>
        </div>

        @if(session('success'))<x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>@endif
        @if(session('warning'))<x-alert type="warning" class="mb-6">{{ session('warning') }}</x-alert>@endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Padres vinculados</h2>
                @if($alumno->padres->isEmpty())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                        <p class="text-sm text-gray-500">Sin padres vinculados</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($alumno->padres as $padre)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr($padre->nombres, 0, 1) }}{{ substr($padre->apellido_paterno, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $padre->nombres }} {{ $padre->apellido_paterno }}</p>
                                            <x-badge variant="info">{{ $padre->pivot->parentesco }}</x-badge>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.alumno-padre.destroy', [$alumno, $padre]) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta vinculación?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Desvincular</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Vincular nuevo padre</h2>
                @if($padresDisponibles->isEmpty())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                        <p class="text-sm text-gray-500">No hay padres disponibles para vincular</p>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <form action="{{ route('admin.alumno-padre.store', $alumno) }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Padre</label>
                                    <select name="padre_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        <option value="">Seleccionar padre</option>
                                        @foreach($padresDisponibles as $padre)
                                            <option value="{{ $padre->id }}">{{ $padre->nombres }} {{ $padre->apellido_paterno }} — DNI: {{ $padre->dni }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Parentesco</label>
                                    <select name="parentesco" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        <option value="padre">Padre</option>
                                        <option value="madre">Madre</option>
                                        <option value="tutor">Tutor</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    Vincular padre
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-administrativo-layout>
