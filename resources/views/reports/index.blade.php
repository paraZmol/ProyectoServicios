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
                    <div class="grid items-end grid-cols-1 gap-6 md:grid-cols-4">

                        {{-- fecha de inicio --}}
                        <div>
                            <label for="start_date" class="block mb-2 text-sm font-bold text-gray-700">Fecha Inicio:</label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ $startDate }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        {{-- fecha de fin --}}
                        <div>
                            <label for="end_date" class="block mb-2 text-sm font-bold text-gray-700">Fecha Fin:</label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ $endDate }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        {{-- atajos --}}
                        <div class="flex space-x-2">
                            <button type="button" onclick="setDateRange('today')" class="px-3 py-2 text-xs font-bold text-indigo-700 bg-indigo-100 rounded hover:bg-indigo-200">Hoy</button>
                            <button type="button" onclick="setDateRange('week')" class="px-3 py-2 text-xs font-bold text-indigo-700 bg-indigo-100 rounded hover:bg-indigo-200">Semana</button>
                            <button type="button" onclick="setDateRange('month')" class="px-3 py-2 text-xs font-bold text-indigo-700 bg-indigo-100 rounded hover:bg-indigo-200">Mes</button>
                        </div>

                        {{-- filtrar --}}
                        <div>
                            <button type="submit" class="flex items-center justify-center w-full px-4 py-2 font-bold text-white transition bg-gray-800 rounded-lg hover:bg-gray-900">
                                <i class="mr-2 fas fa-filter"></i> Filtrar Reporte
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- resumen en trajetas --}}
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                {{-- total ingreos --}}
                <div class="p-6 bg-white border-l-4 border-green-500 shadow-lg rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 bg-green-100 rounded-full">
                            <i class="text-2xl text-green-600 fas fa-dollar-sign"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-xs font-bold text-gray-500 uppercase">Ingresos Totales (Periodo)</p>
                            <p class="text-2xl font-extrabold text-gray-800">S/ {{ number_format($totalIngresos, 2) }}</p>
                        </div>
                    </div>
                </div>

                {{-- cantidad de ventas --}}
                <div class="p-6 bg-white border-l-4 border-blue-500 shadow-lg rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 mr-4 bg-blue-100 rounded-full">
                            <i class="text-2xl text-blue-600 fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-xs font-bold text-gray-500 uppercase">Ventas Realizadas</p>
                            <p class="text-2xl font-extrabold text-gray-800">{{ $cantidadVentas }}</p>
                        </div>
                    </div>
                </div>

                {{-- total anulado --}}
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

            {{-- tabka de resultados --}}
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Detalle de Operaciones</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Boleta</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-center text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-right text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-xs font-bold tracking-wider text-center text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($invoices as $invoice)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900 whitespace-nowrap">
                                        #{{ $invoice->correlativo ?? $invoice->id }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ Str::limit($invoice->client->nombre ?? 'N/A', 20) }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($invoice->estado == 'Pagada')
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">Pagada</span>
                                        @elseif($invoice->estado == 'Pendiente')
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">Pendiente</span>
                                        @else
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">Anulada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-right text-gray-900 whitespace-nowrap">
                                        S/ {{ number_format($invoice->total, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-center whitespace-nowrap">
                                        <a href="{{ route('invoices.show', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        No se encontraron registros en este rango de fechas.
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

            // formato a YYYY-MM-DD
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
                // primer dia - lunes
                const day = today.getDay() || 7;
                if( day !== 1 ) today.setHours(-24 * (day - 1));
                startDateInput.value = formatDate(today);

                // ultimo dia - domingo
                const endWeek = new Date(today);
                endWeek.setDate(today.getDate() + 6);
                endDateInput.value = formatDate(endWeek);
            } else if (type === 'month') {
                // primero dia del mes
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                startDateInput.value = formatDate(firstDay);

                // ultimo dia del mes
                const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                endDateInput.value = formatDate(lastDay);
            }

            // Opcional: Enviar formulario automáticamente
            // document.getElementById('reportForm').submit();
        }
    </script>
</x-app-layout>
