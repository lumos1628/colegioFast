<x-administrativo-layout>
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Panel de Dirección</h1>
            <p class="mt-1 text-sm text-gray-500">Supervisión global del sistema</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Alumnos</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalAlumnos }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Docentes</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalDocentes }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Cursos</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalCursos }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Matrículas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalMatriculas }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($promedioGeneral !== null)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Promedio General del Sistema</h3>
                        <p class="text-sm text-gray-500 mt-1">Promedio de todas las calificaciones del periodo activo</p>
                    </div>
                    <div class="text-right">
                        @php
                            $nivel = match(true) {
                                $promedioGeneral >= 3.5 => ['label' => 'AD', 'variant' => 'success'],
                                $promedioGeneral >= 2.5 => ['label' => 'A', 'variant' => 'primary'],
                                $promedioGeneral >= 1.5 => ['label' => 'B', 'variant' => 'warning'],
                                default => ['label' => 'C', 'variant' => 'danger'],
                            };
                        @endphp
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($promedioGeneral, 2) }}</p>
                        <x-badge :variant="$nivel['variant']">{{ $nivel['label'] }}</x-badge>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Alumnos por Grado</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($alumnosPorGrado as $grado)
                            @php $max = $alumnosPorGrado->max('total'); $pct = $max > 0 ? round(($grado->total / $max) * 100) : 0; @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $grado->grado }}° Grado</span>
                                    <span class="text-sm text-gray-500">{{ $grado->total }} alumnos</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-blue-500" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Incidencias Recientes</h3>
                    <p class="text-sm text-gray-500 mt-1">Últimas 10 incidencias de conducta</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($incidenciasRecientes as $incidencia)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-gray-900">{{ $incidencia->alumno->nombres }} {{ $incidencia->alumno->apellido_paterno }}</p>
                                        <x-badge :variant="match($incidencia->tipo->value) {
                                            'falta_leve' => 'warning',
                                            'falta_grave' => 'danger',
                                            'merito' => 'success',
                                            default => 'default'
                                        }">{{ $incidencia->tipo->label() }}</x-badge>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($incidencia->descripcion, 60) }}</p>
                                </div>
                                <p class="text-xs text-gray-400">{{ $incidencia->fecha->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-gray-500">Sin incidencias recientes</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-administrativo-layout>
