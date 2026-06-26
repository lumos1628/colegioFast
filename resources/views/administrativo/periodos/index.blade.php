<x-administrativo-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Periodos Académicos</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $periodos->count() }} periodos registrados</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-create').classList.remove('hidden')"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nuevo periodo
            </button>
        </div>

        @if(session('success'))<x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>@endif
        @if(session('error'))<x-alert type="danger" class="mb-6">{{ session('error') }}</x-alert>@endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Año</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fechas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($periodos as $periodo)
                        <tr class="hover:bg-gray-50" x-data="{ editing: false }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form :action="editing ? '{{ route('admin.periodos.update', $periodo) }}' : ''" :method="editing ? 'POST' : ''" class="flex items-center gap-2">
                                    @csrf @method('PUT')
                                    <input type="text" name="nombre" :value="'{{ $periodo->nombre }}'" :readonly="!editing" :disabled="!editing"
                                           class="border-0 p-0 text-sm font-medium text-gray-900 bg-transparent focus:ring-0 w-full" :class="editing ? 'border-b border-gray-300' : ''">
                                    <input type="hidden" name="fecha_inicio" value="{{ $periodo->fecha_inicio->format('Y-m-d') }}">
                                    <input type="hidden" name="fecha_fin" value="{{ $periodo->fecha_fin->format('Y-md') }}">
                                    <input type="hidden" name="anio_escolar" value="{{ $periodo->anio_escolar }}">
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $periodo->anio_escolar }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $periodo->fecha_inicio->format('d/m/Y') }} — {{ $periodo->fecha_fin->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($periodo->activo)
                                    <x-badge variant="success">Activo</x-badge>
                                @else
                                    <x-badge variant="default">Inactivo</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-3">
                                    @if(!$periodo->activo)
                                        <form action="{{ route('admin.periodos.activar', $periodo) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 font-medium">Activar</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.periodos.destroy', $periodo) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar este periodo?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium" {{ $periodo->activo ? 'disabled title="No se puede eliminar un periodo activo"' : '' }}>Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-create" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nuevo Periodo Académico</h2>
            <form action="{{ route('admin.periodos.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="nombre" required placeholder="Ej: Bimestre I" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Año Escolar</label>
                        <input type="number" name="anio_escolar" value="{{ date('Y') }}" min="2020" max="2100" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Crear periodo</button>
                </div>
            </form>
        </div>
    </div>
</x-administrativo-layout>
