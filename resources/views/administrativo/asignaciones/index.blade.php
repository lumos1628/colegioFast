<x-administrativo-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Asignaciones</h1>
                <p class="mt-1 text-sm text-gray-500">Docente + Curso + Periodo + Horario</p>
            </div>
            <a href="{{ route('admin.asignaciones.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nueva asignación
            </a>
        </div>

        @if(session('success'))<x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>@endif
        @if(session('error'))<x-alert type="danger" class="mb-6">{{ session('error') }}</x-alert>@endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
            <form method="GET" action="{{ route('admin.asignaciones.index') }}" class="flex flex-wrap gap-3">
                <select name="periodo" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Todos los periodos</option>
                    @foreach($periodos as $p)
                        <option value="{{ $p->id }}" {{ request('periodo') == $p->id ? 'selected' : '' }}>{{ $p->nombre }} ({{ $p->anio_escolar }})</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filtrar</button>
                @if(request('periodo'))<a href="{{ route('admin.asignaciones.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Limpiar</a>@endif
            </form>
        </div>

        @php $diasSemana = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes']; @endphp
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periodo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matrículas</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($asignaciones as $asignacion)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">{{ $asignacion->docente->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $asignacion->docente->especialidad }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-900">{{ $asignacion->curso->nombre }}</p>
                                <p class="text-xs text-gray-500">{{ $asignacion->curso->grado }}° "{{ $asignacion->curso->seccion }}"</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-900">{{ $asignacion->periodoAcademico->nombre }}</p>
                                @if($asignacion->periodoAcademico->activo)<x-badge variant="success" class="mt-1">Activo</x-badge>@endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($asignacion->dia_semana)
                                    {{ $diasSemana[$asignacion->dia_semana] ?? '' }}
                                    {{ $asignacion->hora_inicio ? \Carbon\Carbon::parse($asignacion->hora_inicio)->format('H:i') : '' }}
                                    -
                                    {{ $asignacion->hora_fin ? \Carbon\Carbon::parse($asignacion->hora_fin)->format('H:i') : '' }}
                                @else
                                    <span class="text-gray-400">Sin horario</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $asignacion->matriculas->count() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.asignaciones.edit', $asignacion) }}" class="text-blue-600 hover:text-blue-800 font-medium">Editar</a>
                                    <form action="{{ route('admin.asignaciones.destroy', $asignacion) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar esta asignación?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Sin asignaciones registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $asignaciones->links() }}</div>
    </div>
</x-administrativo-layout>
