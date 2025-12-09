<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Contenedor principal del Dashboard --}}
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

                {{-- Últimas Ventas (Tarjeta Personalizada) --}}
                <div class="p-6 bg-white shadow-2xl rounded-xl">
                    <h2 class="pb-3 mb-4 text-2xl font-bold text-blue-800 border-b border-blue-100">{{ __('Últimas Boletas/Ventas') }}</h2>

                    @if ($ultimasVentas->isEmpty())
                        <p class="text-gray-500">{{ __('No hay ventas recientes para mostrar.') }}</p>
                    @else
                        <table class="min-w-full divide-y divide-blue-200">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-center text-blue-600 uppercase">
                                        {{ __('Boleta N°') }}
                                    </th>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-blue-600 uppercase">
                                        {{ __('Cliente') }}
                                    </th>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-center text-blue-600 uppercase">
                                        {{ __('Fecha') }}
                                    </th>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-right text-blue-600 uppercase">
                                        {{ __('Total') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($ultimasVentas as $venta)
                                    <tr class="hover:bg-blue-50/50">
                                        <td class="px-3 py-3 text-sm font-medium text-center text-gray-900">
                                            {{ $venta->id }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-left text-gray-700 whitespace-nowrap">
                                            {{ $venta->client->nombre ?? 'N/A' }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-center text-gray-500 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-3 py-3 text-sm font-bold text-right text-green-600 whitespace-nowrap">
                                            {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($venta->total, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Servicios Más Solicitados (Tarjeta Personalizada) --}}
                <div class="p-6 bg-white shadow-2xl rounded-xl">
                    <h2 class="pb-3 mb-4 text-2xl font-bold text-indigo-800 border-b border-indigo-100">{{ __('Servicios Más Solicitados') }} </h2>

                    @if ($serviciosMasVendidos->isEmpty())
                        <p class="text-gray-500">{{ __('No hay servicios más vendidos para mostrar.') }}</p>
                    @else
                        <table class="min-w-full divide-y divide-indigo-200">
                            <thead class="bg-indigo-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-[#253891] uppercase">
                                        {{ __('Servicio') }}
                                    </th>
                                    <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-center text-[#253891] uppercase">
                                        {{ __('N° de Ventas') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($serviciosMasVendidos as $servicio)
                                    <tr class="hover:bg-indigo-50/50">
                                        <td class="px-3 py-3 text-sm text-left text-gray-700 whitespace-nowrap">
                                            {{ $servicio->nombre_servicio }}
                                        </td>
                                        <td class="px-3 py-3 text-lg font-extrabold text-center text-[#253891] whitespace-nowrap">
                                            {{ number_format($servicio->total_vendido) }}
                                        </td>
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
