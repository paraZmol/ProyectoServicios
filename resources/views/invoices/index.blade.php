<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold leading-tight text-gray-800">
            {{ __('Gestión de Boletas') }}
        </h2>
    </x-slot>

    <div class="w-full px-4 py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="p-8 overflow-hidden bg-white shadow-2xl sm:rounded-xl">

                {{-- Mensaje de éxito (Estilo moderno) --}}
                @if (session('success'))
                    <div class="p-4 mb-6 font-medium text-white bg-green-500 rounded-lg shadow-md" role="alert">
                        <p class="flex items-center"><i class="mr-2 fas fa-check-circle"></i> {{ session('success') }}</p>
                    </div>
                @endif

                {{-- Header y Búsqueda --}}
                <div class="flex flex-col items-start justify-between mb-8 md:flex-row md:items-center">
                    <form method="GET" action="{{ route('invoices.index') }}" class="flex items-center w-full mb-4 space-x-3 md:mb-0 md:w-auto">

                        <div class="relative w-full md:w-96">
                            <x-text-input
                                type="text"
                                name="search"
                                placeholder="Buscar por N° de boleta, Cliente o Vendedor"
                                value="{{ $search }}"
                                class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />

                            {{-- Botón de Cancelación (visible solo si hay un valor de búsqueda) --}}
                            @if ($search)
                                <a href="{{ route('invoices.index') }}"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 cursor-pointer hover:text-red-500"
                                title="Cancelar Búsqueda">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>

                        <x-primary-button type="submit" class="flex items-center px-4 py-2 font-bold transition duration-150 ease-in-out bg-gray-800 rounded-lg shadow-md hover:bg-gray-900 whitespace-nowrap">
                            <i class="mr-1 fas fa-search"></i> {{ __('Buscar') }}
                        </x-primary-button>
                    </form>

                    <a href="{{ route('invoices.create') }}"
                       class="flex items-center px-5 py-2 font-bold text-white transition duration-150 ease-in-out bg-indigo-600 rounded-lg shadow-lg hover:bg-indigo-700 whitespace-nowrap">
                        <i class="mr-2 fas fa-plus-circle"></i> {{ __('Nueva Boleta') }}
                    </a>
                </div>

                {{-- Tabla de Boletas --}}
                <div class="overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-100/70">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Boleta N°</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Vendedor</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-blue-700 uppercase">Total</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Estado</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($invoices as $invoice)
                                <tr class="transition duration-100 ease-in-out hover:bg-gray-50">
                                    {{-- Boleta N° (Alineado a la derecha para consistencia numérica) --}}
                                    <td class="px-6 py-4 text-sm font-semibold text-right text-gray-900 whitespace-nowrap">#{{ $invoice->id }}</td>

                                    {{-- Fecha --}}
                                    <td class="px-6 py-4 text-sm text-center text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}</td>

                                    {{-- Cliente (Alineado a la izquierda) --}}
                                    <td class="px-6 py-4 text-sm text-left text-gray-800 whitespace-nowrap">{{ $invoice->client->nombre ?? 'N/A' }}</td>

                                    {{-- Vendedor (Alineado a la izquierda) --}}
                                    <td class="px-6 py-4 text-sm text-left text-gray-600 whitespace-nowrap">{{ $invoice->user->name ?? 'N/A' }}</td>

                                    {{-- Total (Alineado a la derecha, color verde de énfasis) --}}
                                    <td class="px-6 py-4 text-sm font-extrabold text-right text-green-700 whitespace-nowrap">
                                        {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->total, 2) }}
                                    </td>

                                    {{-- Estado (Badge) --}}
                                    <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                                        @php
                                            $estado = $invoice->estado ?? 'Pendiente';
                                            $class = match ($estado) {
                                                'Pagada' => 'bg-green-100 text-green-800',
                                                'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-red-100 text-red-800', // ANUALDa
                                            };
                                        @endphp
                                        <span class="inline-flex px-3 py-0.5 text-xs font-semibold rounded-full {{ $class }}">
                                            {{ $estado }}
                                        </span>
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="flex justify-center px-6 py-4 space-x-3 text-sm font-medium whitespace-nowrap">
                                        {{-- Ver Boleta (Botón Primario) --}}
                                        <a href="{{ route('invoices.show', $invoice->id) }}"
                                           class="flex items-center p-2 text-sm font-medium text-blue-700 transition duration-150 ease-in-out bg-blue-100 rounded-full shadow-sm hover:bg-blue-200">
                                            <i class="mr-1 fas fa-eye"></i>Ver
                                        </a>

                                        {{-- Editar --}}
                                        <a href="{{ route('invoices.edit', $invoice->id) }}"
                                           class="flex items-center p-2 text-sm font-medium text-yellow-700 transition duration-150 ease-in-out bg-yellow-100 rounded-full shadow-sm hover:bg-yellow-200">
                                            <i class="mr-1 fas fa-edit"></i>Editar
                                        </a>

                                        {{-- Eliminar (Activa el Modal) --}}
                                        <form id="delete-invoice-form-{{ $invoice->id }}"
                                              action="{{ route('invoices.destroy', $invoice->id) }}"
                                              method="POST"
                                              onsubmit="return false;">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                onclick="showDeleteInvoiceModal('Boleta #{{ $invoice->id }}', 'delete-invoice-form-{{ $invoice->id }}')"
                                                class="flex items-center p-2 text-sm font-medium text-red-700 transition duration-150 ease-in-out bg-red-100 rounded-full shadow-sm hover:bg-red-200">
                                                <i class="mr-1 fas fa-trash"></i>Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-base text-center text-gray-500 bg-gray-50">
                                        <i class="mr-2 fas fa-info-circle"></i> No se encontraron boletas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-8">
                    {{ $invoices->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- 🗑️ Modal de Confirmación Estilizado para Boletas
    {{-- ========================================================= --}}

    <!-- Modal Overlay (fondo oscuro) -->
    <div id="deleteInvoiceModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">

        <!-- Contenedor del Diálogo -->
        <div class="w-full max-w-md p-6 mx-4 transition-all duration-300 transform scale-95 bg-white rounded-lg shadow-2xl opacity-0" id="invoiceModalContent">

            <!-- Icono de Advertencia -->
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <i class="text-2xl text-red-600 fas fa-exclamation-triangle"></i>
            </div>

            <!-- Título y Mensaje -->
            <h3 class="mb-2 text-lg font-semibold text-center text-gray-900">Confirmar Eliminación de Boleta</h3>
            <p id="invoiceModalMessage" class="text-sm text-center text-gray-500">
                ¿Estás seguro de que quieres eliminar esta boleta? Esta acción es irreversible y podría afectar la integridad de los registros de venta.
            </p>

            <!-- Área de Botones -->
            <div class="flex justify-center mt-6 space-x-4">
                <button
                    onclick="hideDeleteInvoiceModal()"
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-gray-700 transition duration-150 bg-gray-200 rounded-lg shadow-sm hover:bg-gray-300">
                    Cancelar
                </button>
                <button
                    onclick="confirmInvoiceDelete()"
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-white transition duration-150 bg-red-600 rounded-lg shadow-md hover:bg-red-700">
                    Sí, Eliminar Boleta
                </button>
            </div>

            <input type="hidden" id="invoiceFormToDelete">
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- 🧠 Script para Controlar el Modal de Boletas
    {{-- ========================================================= --}}

    <script>
        // Referencias al modal y elementos internos
        const invoiceModal = document.getElementById('deleteInvoiceModal');
        const invoiceModalContent = document.getElementById('invoiceModalContent');
        const invoiceModalMessage = document.getElementById('invoiceModalMessage');
        const invoiceFormToDeleteInput = document.getElementById('invoiceFormToDelete');

        /**
         * Muestra el modal de eliminación de boleta.
         */
        function showDeleteInvoiceModal(invoiceId, formId) {
            invoiceModal.classList.remove('hidden');
            invoiceModal.classList.add('flex');

            setTimeout(() => {
                invoiceModalContent.classList.remove('scale-95', 'opacity-0');
                invoiceModalContent.classList.add('scale-100', 'opacity-100');
            }, 50);

            invoiceModalMessage.innerHTML = `¿Estás seguro de que quieres eliminar la <strong>${invoiceId}</strong>? Esta acción es irreversible y podría afectar la integridad de los registros de venta.`;
            invoiceFormToDeleteInput.value = formId;
        }

        /**
         * Oculta el modal de eliminación de boleta.
         */
        function hideDeleteInvoiceModal() {
            invoiceModalContent.classList.remove('scale-100', 'opacity-100');
            invoiceModalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                invoiceModal.classList.add('hidden');
                invoiceModal.classList.remove('flex');
            }, 300);
        }

        /**
         * Confirma la eliminación y envía el formulario.
         */
        function confirmInvoiceDelete() {
            const formId = invoiceFormToDeleteInput.value;
            if (formId) {
                document.getElementById(formId).submit();
                hideDeleteInvoiceModal();
            }
        }

        // Cierre del modal al hacer clic en el fondo oscuro
        invoiceModal.addEventListener('click', (e) => {
            if (e.target.id === 'deleteInvoiceModal') {
                hideDeleteInvoiceModal();
            }
        });

    </script>
</x-app-layout>
