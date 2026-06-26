<x-administrativo-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestión de Alumnos</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $alumnos->total() }} alumnos registrados</p>
            </div>
            <a href="{{ route('admin.alumnos.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo alumno
            </a>
        </div>

        @if(session('success'))
            <x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="danger" class="mb-6">{{ session('error') }}</x-alert>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
            <form method="GET" action="{{ route('admin.alumnos.index') }}" class="flex flex-wrap gap-3">
                <input type="text" name="busqueda" value="{{ $busqueda ?? '' }}" placeholder="Buscar por nombre o DNI..."
                       class="flex-1 min-w-[200px] rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                <select name="grado" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todos los grados</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ ($grado ?? '') == $i ? 'selected' : '' }}>{{ $i }}° grado</option>
                    @endfor
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Filtrar
                </button>
                @if($busqueda || $grado)
                    <a href="{{ route('admin.alumnos.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Limpiar</a>
                @endif
            </form>
        </div>

        @if($alumnos->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Sin resultados</h3>
                <p class="mt-2 text-sm text-gray-500">No se encontraron alumnos con los filtros aplicados.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alumno</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Padres</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($alumnos as $alumno)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellido_paterno, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900">{{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</p>
                                                <p class="text-xs text-gray-500">{{ $alumno->fecha_nacimiento->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $alumno->grado }}° "{{ $alumno->seccion }}"</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $alumno->dni }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.alumno-padre.index', $alumno) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                            Gestionar padres →
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.reportes.libreta', $alumno) }}" class="text-green-600 hover:text-green-800 font-medium" title="Descargar libreta">
                                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Libreta
                                            </a>
                                            <a href="{{ route('admin.alumnos.edit', $alumno) }}" class="text-blue-600 hover:text-blue-800 font-medium">Editar</a>
                                            <form action="{{ route('admin.alumnos.destroy', $alumno) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('¿Eliminar este alumno? Se eliminará también su usuario.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">{{ $alumnos->links() }}</div>
        @endif
    </div>
</x-administrativo-layout>
