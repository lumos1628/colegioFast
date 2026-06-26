<x-administrativo-layout>
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Panel Administrativo</h1>
            <p class="mt-1 text-sm text-gray-500">Resumen general del sistema</p>
        </div>

        @php
            $stats = [
                ['label' => 'Alumnos', 'value' => \App\Models\Alumno::count(), 'icon' => 'users', 'color' => 'blue'],
                ['label' => 'Padres', 'value' => \App\Models\Padre::count(), 'icon' => 'user-group', 'color' => 'violet'],
                ['label' => 'Docentes', 'value' => \App\Models\Docente::count(), 'icon' => 'academic-cap', 'color' => 'emerald'],
                ['label' => 'Cursos', 'value' => \App\Models\Curso::count(), 'icon' => 'book', 'color' => 'indigo'],
                ['label' => 'Matrículas', 'value' => \App\Models\Matricula::count(), 'icon' => 'clipboard', 'color' => 'amber'],
                ['label' => 'Asignaciones', 'value' => \App\Models\Asignacion::count(), 'icon' => 'link', 'color' => 'rose'],
            ];
            $periodoActivo = \App\Models\PeriodoAcademico::where('activo', true)->first();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($stats as $stat)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-lg bg-{{ $stat['color'] }}-100 flex items-center justify-center">
                            @switch($stat['icon'])
                                @case('users')
                                    <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    @break
                                @case('user-group')
                                    <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                                    @break
                                @case('academic-cap')
                                    <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                                    @break
                                @case('book')
                                    <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                    @break
                                @case('clipboard')
                                    <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                    @break
                                @case('link')
                                    <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                    @break
                            @endswitch
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stat['value'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Periodo Activo</h2>
            @if($periodoActivo)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $periodoActivo->nombre }}</p>
                        <p class="text-sm text-gray-500 mt-1">Año escolar {{ $periodoActivo->anio_escolar }}</p>
                        <p class="text-sm text-gray-500">{{ $periodoActivo->fecha_inicio->format('d/m/Y') }} — {{ $periodoActivo->fecha_fin->format('d/m/Y') }}</p>
                    </div>
                    <x-badge variant="success">Activo</x-badge>
                </div>
            @else
                <x-alert type="warning">No hay un periodo académico activo. <a href="{{ route('admin.periodos.index') }}" class="underline font-medium">Gestionar periodos →</a></x-alert>
            @endif
        </div>
    </div>
</x-administrativo-layout>
