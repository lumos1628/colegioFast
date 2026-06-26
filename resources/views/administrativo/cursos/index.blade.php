<x-administrativo-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestión de Cursos</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $cursos->total() }} cursos registrados</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-create').classList.remove('hidden')"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nuevo curso
            </button>
        </div>

        @if(session('success'))<x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>@endif
        @if(session('error'))<x-alert type="danger" class="mb-6">{{ session('error') }}</x-alert>@endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
            <form method="GET" action="{{ route('admin.cursos.index') }}" class="flex flex-wrap gap-3">
                <select name="grado" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todos los grados</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ ($grado ?? '') == $i ? 'selected' : '' }}>{{ $i }}° grado</option>
                    @endfor
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filtrar</button>
                @if($grado)<a href="{{ route('admin.cursos.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Limpiar</a>@endif
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grado/Sección</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asignaciones</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($cursos as $curso)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr($curso->nombre, 0, 2) }}
                                    </div>
                                    <p class="ml-3 text-sm font-medium text-gray-900">{{ $curso->nombre }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $curso->area_curricular }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $curso->grado }}° "{{ $curso->seccion }}"</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $curso->asignaciones->count() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <form action="{{ route('admin.cursos.destroy', $curso) }}" method="POST" class="inline"
                                      onsubmit="return confirm('¿Eliminar este curso?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Sin cursos registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $cursos->links() }}</div>
    </div>

    <div id="modal-create" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nuevo Curso</h2>
            <form action="{{ route('admin.cursos.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="nombre" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Área Curricular</label>
                        <input type="text" name="area_curricular" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Grado</label>
                            <select name="grado" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                @for($i = 1; $i <= 6; $i++)<option value="{{ $i }}">{{ $i }}°</option>@endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sección</label>
                            <input type="text" name="seccion" value="A" maxlength="1" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm uppercase">
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Crear curso</button>
                </div>
            </form>
        </div>
    </div>
</x-administrativo-layout>
