<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold leading-tight text-gray-800">
            {{ __('Reportes Históricos de Ventas') }}
        </h2>
    </x-slot>

    <div class="w-full px-4 py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            {{-- seccion de filtros --}}
            <div class="p-6 mb-8 bg-white shadow-xl sm:rounded-xl">
                <form action="{{ route('reports.index') }}" method="GET" id="reportForm">
                    {{-- ajuste de grilla para soportar mas filtros --}}
                    <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-6">

                        {{-- fecha de inicio --}}
                        <div class="md:col-span-1">
                            <label for="start_date" class="block mb-1 text-xs font-bold text-gray-700">Desde:</label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ $startDate }}"
                                class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        {{-- fecha de fin --}}
                        <div class="md:col-span-1">
                            <label for="end_date" class="block mb-1 text-xs font-bold text-gray-700">Hasta:</label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ $endDate }}"
                                class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        {{-- filtro de vendedor --}}
                        <div class="md:col-span-1">
                            <label for="user_id" class="block mb-1 text-xs font-bold text-gray-700">Vendedor:</label>
                            <select name="user_id" id="user_id" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- filtro de estado --}}
                        <div class="md:col-span-1">
                            <label for="status" class="block mb-1 text-xs font-bold text-gray-700">Estado:</label>
                            <select name="status" id="status" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos</option>
                                <option value="Pagada" {{ $status == 'Pagada' ? 'selected' : '' }}>Pagada</option>
                                <option value="Pendiente" {{ $status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Anulada" {{ $status == 'Anulada' ? 'selected' : '' }}>Anulada</option>
                            </select>
                        </div>

                        {{-- botones de accion --}}
                        <div class="flex space-x-2 md:col-span-2">
                            <button type="submit" class="flex items-center justify-center flex-1 px-4 py-2 text-sm font-bold text-white transition bg-gray-800 rounded-lg hover:bg-gray-900">
                                <i class="mr-2 fas fa-filter"></i> Filtrar
                            </button>

                            <button type="submit" formaction="{{ route('reports.pdf') }}" formtarget="_blank" class="flex items-center justify-center flex-1 px-4 py-2 text-sm font-bold text-white transition bg-red-600 rounded-lg hover:bg-red-700">
                                <i class="mr-2 fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>

                    {{-- atajos de fecha --}}
                    <div class="flex flex-wrap items-center justify-end mt-4 space-x-2">
                        <span class="mr-2 text-xs font-bold text-gray-500 uppercase">Rangos Rápidos:</span>
                        <button type="button" onclick="setDateRange('today')" class="px-3 py-1 text-xs font-bold text-indigo-700 transition bg-indigo-100 rounded-md hover:bg-indigo-200">Hoy</button>
                        <button type="button" onclick="setDateRange('week')" class="px-3 py-1 text-xs font-bold text-indigo-700 transition bg-indigo-100 rounded-md hover:bg-indigo-200">Esta Semana</button>
                        <button type="button" onclick="setDateRange('month')" class="px-3 py-1 text-xs font-bold text-indigo-700 transition bg-indigo-100 rounded-md hover:bg-indigo-200">Este Mes</button>
                    </div>
                </form>
            </div>

            {{-- resumen en tarjetas --}}
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                <div class="p-6 bg-white border-l-4 border-green-500 shadow-lg rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 bg-green-100 rounded-full">
                            <i class="text-2xl text-green-600 fas fa-dollar-sign"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-xs font-bold text-gray-500 uppercase">Ingresos (Selección)</p>
                            <p class="text-2xl font-extrabold text-gray-800">S/ {{ number_format($totalIngresos, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border-l-4 border-blue-500 shadow-lg rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 bg-blue-100 rounded-full">
                            <i class="text-2xl text-blue-600 fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-xs font-bold text-gray-500 uppercase">Ventas (Selección)</p>
                            <p class="text-2xl font-extrabold text-gray-800">{{ $cantidadVentas }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border-l-4 border-red-500 shadow-lg rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 bg-red-100 rounded-full">
                            <i class="text-2xl text-red-600 fas fa-ban"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-xs font-bold text-gray-500 uppercase">Monto Anulado</p>
                            <p class="text-2xl font-extrabold text-gray-800">S/ {{ number_format($totalAnulado, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- tabla de resultados --}}
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Detalle de Operaciones</h3>
                    <span class="text-xs text-gray-500">{{ count($invoices) }} registros encontrados</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Fecha</th>
                                {{-- Cambio: Columna Detalle unificada --}}
                                <th class="w-1/2 px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Detalle de Venta</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Vendedor</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-right text-gray-500 uppercase">Total / Ingreso</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-center text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($invoices as $invoice)
                                <tr class="hover:bg-gray-50">
                                    {{-- FECHA --}}
                                    <td class="px-6 py-4 text-sm text-gray-500 align-top whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}
                                    </td>

                                    {{-- DETALLE --}}
                                    <td class="px-6 py-4 text-sm text-gray-700 align-top">
                                        <div class="flex items-center mb-1">
                                            @if($invoice->estado == 'Anulada')
                                                <span class="px-2 mr-2 text-xs font-bold text-red-700 bg-red-100 rounded-md">ANULADA</span>
                                            @elseif($invoice->estado == 'Pendiente')
                                                <span class="px-2 mr-2 text-xs font-bold text-yellow-700 bg-yellow-100 rounded-md">PENDIENTE</span>
                                            @else
                                                <span class="px-2 mr-2 text-xs font-bold text-green-700 bg-green-100 rounded-md">PAGADA</span>
                                            @endif

                                            <span class="font-bold text-gray-900">Recibo #{{ $invoice->correlativo ?? str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</span>
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            @foreach($invoice->details as $detail)
                                                <div class="truncate">• {{ $detail->nombre_servicio }} (x{{ $detail->cantidad }})</div>
                                            @endforeach
                                        </div>
                                        {{-- Mostrar cliente también en el detalle --}}
                                        <div class="mt-1 text-xs italic text-gray-400">
                                            Cliente: {{ Str::limit($invoice->client->nombre ?? 'N/A', 30) }}
                                        </div>
                                    </td>

                                    {{-- VENDEDOR --}}
                                    <td class="px-6 py-4 text-sm text-gray-700 align-top">
                                        {{ Str::limit($invoice->user->name ?? 'Sistema', 20) }}
                                    </td>

                                    {{-- TOTAL / INGRESO --}}
                                    <td class="px-6 py-4 text-sm font-bold text-right text-gray-900 align-top whitespace-nowrap">
                                        @if($invoice->estado == 'Pendiente')
                                            {{-- Monto Pagado --}}
                                            <div class="text-gray-900">S/ {{ number_format($invoice->monto_pagado, 2) }}</div>
                                            {{-- Deuda --}}
                                            <div class="mt-1 text-xs font-normal text-red-500">
                                                (Falta: {{ number_format($invoice->total - $invoice->monto_pagado, 2) }})
                                            </div>
                                        @elseif($invoice->estado == 'Anulada')
                                            <span class="text-gray-400 line-through">
                                                S/ {{ number_format($invoice->total, 2) }}
                                            </span>
                                        @else
                                            S/ {{ number_format($invoice->total, 2) }}
                                        @endif
                                    </td>

                                    {{-- ACCIÓN --}}
                                    <td class="px-6 py-4 text-sm font-medium text-center align-top whitespace-nowrap">
                                        <a href="{{ route('invoices.show', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        No se encontraron registros con los filtros seleccionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- para los filtros rapidos --}}
    <script>
        function setDateRange(type) {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const today = new Date();

            const formatDate = (date) => {
                let d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;

                return [year, month, day].join('-');
            }

            if (type === 'today') {
                startDateInput.value = formatDate(today);
                endDateInput.value = formatDate(today);
            } else if (type === 'week') {
                const day = today.getDay() || 7;
                const startOfWeek = new Date(today);
                startOfWeek.setDate(today.getDate() - day + 1);
                startDateInput.value = formatDate(startOfWeek);

                const endWeek = new Date(startOfWeek);
                endWeek.setDate(startOfWeek.getDate() + 6);
                endDateInput.value = formatDate(endWeek);
            } else if (type === 'month') {
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                startDateInput.value = formatDate(firstDay);

                const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                endDateInput.value = formatDate(lastDay);
            }
        }
    </script>
</x-app-layout>
