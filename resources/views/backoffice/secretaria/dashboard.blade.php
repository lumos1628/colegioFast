<x-administrativo-layout>
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Panel de Secretaría</h1>
            <p class="mt-1 text-sm text-gray-500">Gestión operativa del sistema</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Alumnos</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalAlumnos }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Matrículas Recientes</h3>
                    <p class="text-sm text-gray-500 mt-1">Últimas 10 matrículas del periodo activo</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($matriculasRecientes as $matricula)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $matricula->alumno->nombres }} {{ $matricula->alumno->apellido_paterno }}</p>
                                    <p class="text-xs text-gray-500">{{ $matricula->asignacion->curso->nombre }} — {{ $matricula->asignacion->curso->grado }}°{{ $matricula->asignacion->curso->seccion }}</p>
                                </div>
                                <p class="text-xs text-gray-400">{{ $matricula->fecha_matricula->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-gray-500">Sin matrículas recientes</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pagos Pendientes</h3>
                    <p class="text-sm text-gray-500 mt-1">Próximos vencimientos</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($pagosPendientes as $pago)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $pago->alumno->nombres }} {{ $pago->alumno->apellido_paterno }}</p>
                                    <p class="text-xs text-gray-500">{{ $pago->concepto }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">S/ {{ number_format($pago->monto, 2) }}</p>
                                    <p class="text-xs text-gray-400">{{ $pago->fecha_vencimiento->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-gray-500">Sin pagos pendientes</div>
                    @endforelse
                </div>
            </div>
        </div>

        @if($alumnosSinMatricula->isNotEmpty())
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Alumnos sin Matrícula</h3>
                    <p class="text-sm text-gray-500 mt-1">Alumnos que no tienen matrícula en el periodo activo</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($alumnosSinMatricula as $alumno)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $alumno->nombres }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</p>
                                <p class="text-xs text-gray-500">{{ $alumno->grado }}° "{{ $alumno->seccion }}" — DNI: {{ $alumno->dni }}</p>
                            </div>
                            <a href="{{ route('admin.matriculas.create') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Matricular →</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-administrativo-layout>
