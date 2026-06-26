<x-psicologo-layout :psicologo="$psicologo" :alumnos-atendidos="$alumnosAtendidos">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Bitácora Psicológica</h1>
            <p class="mt-1 text-sm text-gray-500">Registro privado de seguimiento psicológico</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-teal-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Bitácoras</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalBitacoras }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Alumnos Atendidos</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalAlumnos }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-center h-full">
                    <a href="{{ route('psicologo.bitacoras.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Nueva bitácora
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">Bitácoras Recientes</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($bitacorasRecientes as $bitacora)
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-gray-900">{{ $bitacora->alumno->nombres }} {{ $bitacora->alumno->apellido_paterno }} {{ $bitacora->alumno->apellido_materno }}</p>
                                    <span class="text-xs text-gray-500">{{ $bitacora->alumno->grado }}°{{ $bitacora->alumno->seccion }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($bitacora->observaciones, 120) }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $bitacora->fecha->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('psicologo.bitacoras.edit', $bitacora) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Editar</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-500">Sin bitácoras registradas</div>
                @endforelse
            </div>
        </div>
    </div>
</x-psicologo-layout>
