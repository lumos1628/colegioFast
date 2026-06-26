<x-padre-layout :padre="$padre" :hijos="$hijos">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[
            ['label' => 'Panel', 'url' => route('padre.dashboard')],
            ['label' => 'Estado Financiero']
        ]" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Estado Financiero</h1>
            <p class="mt-1 text-sm text-gray-500">Pagos y pensiones de tus hijos</p>
        </div>

        @if($hijos->isEmpty())
            <x-alert type="warning" title="Sin hijos registrados">
                No tienes hijos vinculados a tu cuenta.
            </x-alert>
        @else
            <div class="space-y-6">
                @foreach($pagosPorHijo as $data)
                    @php
                        $hijo = $data['hijo'];
                        $pagos = $data['pagos'];
                        $totalPagado = $data['total_pagado'];
                        $totalPendiente = $data['total_pendiente'];
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($hijo->nombres, 0, 1) }}{{ substr($hijo->apellido_paterno, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $hijo->nombres }} {{ $hijo->apellido_paterno }}</h3>
                                        <p class="text-sm text-gray-500">{{ $hijo->grado }}° "{{ $hijo->seccion }}"</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Pendiente</p>
                                    <p class="text-xl font-bold text-gray-900">S/ {{ number_format($totalPendiente, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        @if($pagos->isEmpty())
                            <div class="p-8 text-center">
                                <p class="text-sm text-gray-500">Sin pagos registrados</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concepto</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periodo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimiento</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($pagos as $pago)
                                            <tr>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $pago->concepto }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-500">{{ $pago->periodoAcademico->nombre ?? '-' }}</td>
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">S/ {{ number_format($pago->monto, 2) }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-500">{{ $pago->fecha_vencimiento->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $variant = match($pago->estado->value) {
                                                            'pagado' => 'success',
                                                            'pendiente' => 'warning',
                                                            'vencido' => 'danger',
                                                            default => 'default',
                                                        };
                                                    @endphp
                                                    <x-badge :variant="$variant">{{ $pago->estado->label() }}</x-badge>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-4 bg-gray-50 border-t border-gray-200 flex flex-wrap gap-6">
                                <div>
                                    <p class="text-xs text-gray-500">Total pagado</p>
                                    <p class="text-sm font-semibold text-green-600">S/ {{ number_format($totalPagado, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Total pendiente</p>
                                    <p class="text-sm font-semibold text-red-600">S/ {{ number_format($totalPendiente, 2) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-padre-layout>
