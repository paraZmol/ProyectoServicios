<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Facturas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                @if (session('success'))
                    <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                {{-- eader y busqueda --}}
                <div class="flex items-center justify-between mb-6">
                    <form method="GET" action="{{ route('invoices.index') }}" class="flex space-x-2">
                        <x-text-input type="text" name="search" placeholder="Buscar por ID, Cliente o Vendedor" value="{{ $search }}" class="w-64" />
                        <x-primary-button type="submit">Buscar</x-primary-button>
                    </form>

                    <a href="{{ route('invoices.create') }}" class="px-4 py-2 font-bold text-white bg-indigo-600 rounded shadow hover:bg-indigo-700">
                        <i class="mr-1 fa fa-plus-circle"></i> {{ __('Nueva Boleta') }}
                    </a>
                </div>

                {{-- tabla de boletas --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Vendedor</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">#{{ $invoice->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $invoice->client->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $invoice->user->name ?? 'N/A' }}</td>
                                    {{-- ajustes para la moneda --}}
                                    <td class="px-6 py-4 text-sm font-semibold text-right whitespace-nowrap">{{ $setting->simbolo_moneda ?? '$' }} {{ number_format($invoice->total, 2) }}</td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap">
                                        @if ($invoice->estado == 'Pagada')
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">Pagada</span>
                                        @elseif ($invoice->estado == 'Pendiente')
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">Pendiente</span>
                                        @else
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">Cancelada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 space-x-2 text-sm font-medium text-right whitespace-nowrap">
                                        <a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>

                                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de que desea eliminar esta factura?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500">No se encontraron facturas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
