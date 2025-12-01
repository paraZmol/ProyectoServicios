
<x-app-layout><x-slot name="header"><h2 class="text-3xl font-extrabold leading-tight text-gray-800">{{ __('🗑️ Papelera de Boletas Eliminadas') }}</h2></x-slot><div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

        {{-- mensaje --}}
        @if (session('success'))
            <div class="p-4 mb-6 font-medium text-white bg-green-500 rounded-lg shadow-md" role="alert">
                <p class="flex items-center"><i class="mr-2 fas fa-check-circle"></i> {{ session('success') }}</p>
            </div>
        @endif

        <div class="p-8 overflow-hidden bg-white shadow-2xl sm:rounded-xl">

            <div class="mb-8">
                <p class="text-gray-600">Mostrando boletas marcadas como eliminadas. Los administradores pueden ver y restaurar estos documentos fiscales.</p>
            </div>

            <hr class="mb-6 border-gray-100">

            {{-- tabla --}}
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-100/70">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Boleta N°</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-blue-700 uppercase">Total</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-red-700 uppercase">Eliminado el</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($invoices as $invoice)
                            <tr class="transition duration-100 ease-in-out hover:bg-red-50">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800 whitespace-nowrap">{{ $invoice->id }}</td>
                                {{-- cliente - boleto --}}
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $invoice->client->nombre ?? 'Cliente Desconocido' }}</td>

                                <td class="px-6 py-4 text-sm font-bold text-right text-green-600 whitespace-nowrap">
                                    {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->total, 2) }}
                                </td>

                                {{-- fecha --}}
                                <td class="px-6 py-4 text-sm text-center text-red-600">
                                    {{ $invoice->deleted_at ? \Carbon\Carbon::parse($invoice->deleted_at)->format('d/m/Y h:i A') : 'N/A' }}
                                </td>

                                {{-- acciones --}}
                                <td class="flex justify-center px-6 py-4 space-x-3 text-sm font-medium whitespace-nowrap">
                                    <form action="{{ route('invoices.restore', $invoice->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="flex items-center p-2 text-sm font-medium text-green-700 transition duration-150 ease-in-out bg-green-100 rounded-full shadow-sm hover:bg-green-200">
                                            <i class="mr-1 fas fa-undo"></i> Restaurar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-base text-center text-gray-500 bg-gray-50">
                                    <i class="mr-2 fas fa-box-open"></i> No hay boletas eliminadas en la papelera.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- paginacion --}}
            <div class="mt-8">
                {{ $invoices->links() }}
            </div>

        </div>
    </div>
</div>
</x-app-layout>
