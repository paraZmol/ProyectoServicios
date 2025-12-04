<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Boleta #') }}{{ $invoice->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- notificaciones para la boleta --}}
            @if (session('success'))
                <div class="p-4 mb-4 font-medium text-white bg-green-500 rounded-lg shadow-md" role="alert">
                    <p class="flex items-center"><i class="mr-2 fas fa-check-circle"></i> {{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-4 font-medium text-white bg-red-500 rounded-lg shadow-md" role="alert">
                    <p class="flex items-center"><i class="mr-2 fas fa-exclamation-triangle"></i> {{ session('error') }}</p>
                </div>
            @endif
            {{-- fin notificaicon --}}

            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- boton de accion --}}
                    <div class="flex justify-end mb-6 space-x-3">
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="px-4 py-2 font-bold text-white bg-indigo-600 rounded shadow hover:bg-indigo-700">
                            <i class="mr-1 fa fa-edit"></i> {{ __('Editar Boleta') }}
                        </a>
                        {{-- <button onclick="window.print()" class="px-4 py-2 font-bold text-white bg-gray-400 rounded shadow hover:bg-gray-500">
                            <i class="mr-1 fa fa-print"></i> {{ __('Imprimir') }}
                        </button> --}}

                        {{-- Botón Nuevo: Ticket --}}
                        <a href="{{ route('invoices.ticket', $invoice->id) }}" target="_blank" class="px-4 py-2 font-bold text-white bg-gray-800 rounded shadow hover:bg-gray-900">
                            <i class="mr-1 fas fa-receipt"></i> {{ __('Ticket') }}
                        </a>

                        <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank" class="px-4 py-2 font-bold text-white bg-gray-400 rounded shadow hover:bg-gray-500">
                            <i class="mr-1 fa fa-print"></i> {{ __('Imprimir/PDF') }}
                        </a>
                    </div>

                    {{-- diseño de la boleta --}}
                    <div id="invoice-content" class="p-8 border border-gray-200 rounded-lg">

                        {{-- header logo y datos --}}
                        <div class="flex justify-between pb-4 mb-6 border-b">
                            <div>
                                <h1 class="text-2xl font-extrabold text-gray-800">{{ $setting->nombre_empresa ?? 'Mi Empresa' }}</h1>
                                <p class="text-sm text-gray-600">{{ $setting->direccion ?? 'Dirección No Definida' }}</p>
                                <p class="text-sm text-gray-600">{{ $setting->telefono ?? 'Teléfono No Definido' }} | {{ $setting->correo_electronico ?? 'Email No Definido' }}</p>
                            </div>
                            <div>
                                @if ($setting && $setting->logo_path)
                                    <img src="{{ Storage::url($setting->logo_path) }}" alt="Logo" class="object-contain w-auto h-16">
                                @else
                                    <p class="text-xl font-bold text-gray-500">LOGO</p>
                                @endif
                            </div>
                        </div>

                        {{-- info de boleta de cliente --}}
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div>
                                <h2 class="mb-2 text-lg font-bold">{{ __('Boleta A:') }}</h2>
                                <p class="font-semibold">{{ $invoice->client->nombre ?? 'Cliente Eliminado' }}</p>
                                <p class="text-sm">{{ $invoice->client->dni ?? 'DNI no disponible' }}</p>
                                <p class="text-sm">{{ $invoice->client->direccion ?? 'Dirección no disponible' }}</p>
                                <p class="text-sm">{{ $invoice->client->email ?? 'Email no disponible' }}</p>
                                <p class="text-sm">{{ $invoice->client->telefono ?? 'Teléfono no disponible' }}</p>
                            </div>
                            <div class="text-left">
                                <h2 class="mb-2 text-lg font-bold">{{ __('Detalles de Boleta') }}</h2>
                                <p><strong>Nº Boleta:</strong> <span class="text-xl font-extrabold text-indigo-600">#{{ $invoice->id }}</span></p>
                                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}</p>
                                <p><strong>Vendedor:</strong> {{ $invoice->user->name ?? 'N/A' }}</p>
                                <p><strong>Estado:</strong>
                                    <span class="font-bold
                                        @if ($invoice->estado == 'Pagada') text-green-600
                                        @elseif ($invoice->estado == 'Pendiente') text-yellow-600
                                        @else text-red-600
                                        @endif
                                    ">
                                        {{ $invoice->estado }}
                                    </span>
                                </p>
                                <p><strong>Método de Pago:</strong> {{ $invoice->metodo_pago }}</p>
                            </div>
                        </div>

                        {{-- tabbla de items --}}
                        <div class="mb-8 overflow-x-auto">
                            <table class="min-w-full border divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Servicio</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Precio Unitario</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Cantidad</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($invoice->details as $detail)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $detail->nombre_servicio }}</td>
                                            <td class="px-6 py-4 text-sm text-right whitespace-nowrap">{{ $setting->simbolo_moneda ?? '$' }} {{ number_format($detail->precio_unitario_final, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-right whitespace-nowrap">{{ $detail->cantidad }}</td>
                                            <td class="px-6 py-4 text-sm font-bold text-right whitespace-nowrap">{{ $setting->simbolo_moneda ?? '$' }} {{ number_format($detail->total_linea, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- totales --}}
                        <div class="flex justify-end">
                            <div class="w-full pt-4 space-y-2 border-t md:w-1/3">
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span class="font-semibold">{{ $setting->simbolo_moneda ?? '$' }} {{ number_format($invoice->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Impuesto ({{ $setting->iva_porcentaje ?? 'N/A' }}%):</span>
                                    <span class="font-semibold">{{ $setting->simbolo_moneda ?? '$' }} {{ number_format($invoice->impuesto, 2) }}</span>
                                </div>
                                <div class="flex justify-between pt-2 text-xl font-extrabold border-t">
                                    <span>TOTAL:</span>
                                    <span class="text-indigo-600">{{ $setting->simbolo_moneda ?? '$' }} {{ number_format($invoice->total, 2) }}</span>
                                </div>

                                {{-- saldos pendientes --}}
                                @if($invoice->estado == 'Pendiente')
                                    <div class="pt-2 mt-2 border-t border-gray-300 border-dashed">
                                        <div class="flex justify-between text-gray-600">
                                            <span>A cuenta (Pagado):</span>
                                            <span>{{ $setting->simbolo_moneda ?? '$' }} {{ number_format($invoice->monto_pagado, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-lg font-bold text-red-600">
                                            <span>Saldo Pendiente:</span>
                                            <span>{{ $setting->simbolo_moneda ?? '$' }} {{ number_format($invoice->total - $invoice->monto_pagado, 2) }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
