<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <style>
        .dashboard-table-container {
            width: 100%;
            border-collapse: collapse;
        }
        .dashboard-table-container thead {
            background-color: #f3f4f6;
        }
        .dashboard-table-container th {
            padding: 0.75rem 0.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .dashboard-table-container td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
            color: #1f2937;
            border-top: 1px solid #e5e7eb;
        }
        .dashboard-table-container tr:nth-child(even) {
            background-color: #f9fafb;
        }
    </style>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- contenedor principalñ --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- ultimas ventasS --}}
                <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <h2 class="pb-2 mb-4 text-xl font-semibold border-b">{{ __('Últimas Boletas/Ventas') }}</h2>

                    @if ($ultimasVentas->isEmpty())
                        <p class="text-gray-500">{{ __('No hay ventas recientes para mostrar.') }}</p>
                    @else
                        <table class="dashboard-table-container">
                            <thead>
                                <tr>
                                    <th class="px-3 text-right">{{ __('Boleta N°') }}</th>
                                    <th class="px-3">{{ __('Cliente') }}</th>
                                    <th class="px-3">{{ __('Fecha') }}</th>
                                    <th class="px-3">{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($ultimasVentas as $venta)
                                    <tr>
                                        <td class="px-3 text-center">{{ $venta->id }}</td>
                                        <td class="px-3">{{ $venta->client->nombre ?? 'N/A' }}</td>
                                        <td class="px-3">{{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y') }}</td>
                                        <td class="px-3 text-right">
                                            {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($venta->total, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- servicios mas silicitados --}}
                <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <h2 class="pb-2 mb-4 text-xl font-semibold border-b">{{ __('Servicios Más Solicitados') }} </h2>

                    @if ($serviciosMasVendidos->isEmpty())
                        <p class="text-gray-500">{{ __('No hay servicios más vendidos para mostrar.') }}</p>
                    @else
                        <table class="dashboard-table-container">
                            <thead>
                                <tr>
                                    <th class="px-3">{{ __('Servicio') }}</th>
                                    <th class="px-3 text-center">{{ __('N° de Ventas') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($serviciosMasVendidos as $servicio)
                                    <tr>
                                        <td class="px-3">{{ $servicio->nombre_servicio }}</td>
                                        <td class="px-3 text-center">{{ number_format($servicio->total_vendido) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
