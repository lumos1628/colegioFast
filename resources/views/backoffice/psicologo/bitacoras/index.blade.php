<x-psicologo-layout :psicologo="$psicologo" :alumnos-atendidos="$alumnosAtendidos">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[['label' => 'Bitácora Psicológica', 'url' => route('psicologo.dashboard')], ['label' => 'Lista de bitácoras']]" />

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Bitácoras</h1>
                <p class="mt-1 text-sm text-gray-500">Registro de seguimiento psicológico</p>
            </div>
            <a href="{{ route('psicologo.bitacoras.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nueva bitácora
            </a>
        </div>

        @if(session('success'))<x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>@endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
            <form method="GET" action="{{ route('psicologo.bitacoras.index') }}" class="flex flex-wrap gap-3">
                <select name="alumno" class="rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm">
                    <option value="">Todos los alumnos</option>
                    @foreach($alumnosAtendidos as $alumno)
                        <option value="{{ $alumno->id }}" {{ request('alumno') == $alumno->id ? 'selected' : '' }}>{{ $alumno->nombres }} {{ $alumno->apellido_paterno }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filtrar</button>
                @if(request('alumno'))<a href="{{ route('psicologo.bitacoras.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Limpiar</a>@endif
            </form>
        </div>

        @if($bitacoras->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Sin bitácoras</h3>
                <p class="mt-2 text-sm text-gray-500">No se encontraron bitácoras con los filtros aplicados.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bitacoras as $bitacora)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr($bitacora->alumno->nombres, 0, 1) }}{{ substr($bitacora->alumno->apellido_paterno, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $bitacora->alumno->nombres }} {{ $bitacora->alumno->apellido_paterno }} {{ $bitacora->alumno->apellido_materno }}</p>
                                        <p class="text-xs text-gray-500">{{ $bitacora->alumno->grado }}° "{{ $bitacora->alumno->seccion }}" — DNI: {{ $bitacora->alumno->dni }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-700">{{ $bitacora->observaciones }}</p>
                                </div>
                                <p class="text-xs text-gray-400 mt-3">{{ $bitacora->fecha->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3 ml-4">
                                <a href="{{ route('psicologo.bitacoras.edit', $bitacora) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Editar</a>
                                <form action="{{ route('psicologo.bitacoras.destroy', $bitacora) }}" method="POST" class="inline"
                                      onsubmit="return confirm('¿Eliminar esta bitácora?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($bitacoras->hasPages())
                <div class="mt-6">{{ $bitacoras->links() }}</div>
            @endif
        @endif
    </div>
</x-psicologo-layout>
