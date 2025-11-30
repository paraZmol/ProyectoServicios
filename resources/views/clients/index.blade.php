<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold leading-tight text-gray-800">
            {{ __('Gestión de Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            {{-- Mensaje de éxito (Estilo moderno) --}}
            @if (session('success'))
                <div class="p-4 mb-6 font-medium text-white bg-green-500 rounded-lg shadow-md" role="alert">
                    <p class="flex items-center"><i class="mr-2 fas fa-check-circle"></i> {{ session('success') }}</p>
                </div>
            @endif

            <div class="p-8 overflow-hidden bg-white shadow-2xl sm:rounded-xl">

                {{-- FORMULARIO DE BÚSQUEDA Y BOTÓN DE ACCIÓN --}}
                <div class="flex flex-col items-start justify-between mb-8 md:flex-row md:items-center">

                    {{-- Formulario de Búsqueda (w-96) --}}
                    <form method="GET" action="{{ route('clients.index') }}" class="flex items-center w-full mb-4 space-x-3 md:mb-0 md:w-auto">
                        <x-text-input
                            type="text"
                            name="search"
                            placeholder="Nombre, Email o DNI del cliente"
                            value="{{ $search }}"
                            class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 md:w-96" />

                        <x-primary-button type="submit" class="flex items-center px-4 py-2 font-bold transition duration-150 ease-in-out bg-gray-800 rounded-lg shadow-md hover:bg-gray-900">
                             <i class="mr-1 fas fa-search"></i> {{ __('Buscar') }}
                        </x-primary-button>
                    </form>

                    {{-- Botón Nuevo Cliente --}}
                    <a href="{{ route('clients.create') }}"
                       class="flex items-center px-5 py-2 font-bold text-white transition duration-150 ease-in-out bg-indigo-600 rounded-lg shadow-lg hover:bg-indigo-700 whitespace-nowrap">
                        <i class="mr-2 fas fa-plus-circle"></i>{{ __('Nuevo Cliente') }}
                    </a>
                </div>

                <hr class="mb-6 border-gray-100">

                {{-- TABLA DE RESULTADOS --}}
                <div class="overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-100/70">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Nombre / DNI</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Contacto</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Dirección</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Estado</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($clients as $client)
                                <tr class="transition duration-100 ease-in-out hover:bg-gray-50">
                                    {{-- Nombre y DNI (COMBINADOS) --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $client->nombre }}</div>
                                        <div class="text-xs text-gray-500">DNI: {{ $client->dni ?? 'N/A' }}</div>
                                    </td>

                                    {{-- Contacto (COMBINADOS) --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-800 whitespace-nowrap"><i class="mr-1 text-gray-400 fas fa-envelope"></i> {{ $client->email ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500 whitespace-nowrap"><i class="mr-1 text-gray-400 fas fa-phone"></i> {{ $client->telefono ?? 'N/A' }}</div>
                                    </td>

                                    {{-- Dirección --}}
                                    <td class="max-w-sm px-6 py-4 text-sm text-gray-600">{{ $client->direccion ?? 'N/A' }}</td>

                                    {{-- Estado (Con Badge/Pill) --}}
                                    <td class="px-6 py-4 text-sm text-center">
                                        @php
                                            $estado = strtolower($client->estado ?? 'inactivo');
                                            $colorClass = $estado === 'activo' ? 'bg-green-100 text-green-800' :
                                                          ($estado === 'inactivo' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600');
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                            {{ ucfirst($estado) }}
                                        </span>
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="flex justify-center px-6 py-4 space-x-3 text-sm font-medium whitespace-nowrap">
                                        {{-- Botón Editar --}}
                                        <a href="{{ route('clients.edit', $client) }}"
                                            class="flex items-center p-2 text-sm font-medium text-yellow-700 transition duration-150 ease-in-out bg-yellow-100 rounded-full shadow-sm hover:bg-yellow-200">
                                            <i class="mr-1 fas fa-edit"></i> Editar
                                        </a>

                                        {{-- Formulario Eliminar (Activa el Modal) --}}
                                        <form id="delete-client-form-{{ $client->id }}"
                                              action="{{ route('clients.destroy', $client) }}"
                                              method="POST"
                                              onsubmit="return false;">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                onclick="showDeleteClientModal('{{ $client->nombre }}', 'delete-client-form-{{ $client->id }}')"
                                                class="flex items-center p-2 text-sm font-medium text-red-700 transition duration-150 ease-in-out bg-red-100 rounded-full shadow-sm hover:bg-red-200">
                                                <i class="mr-1 fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-base text-center text-gray-500 bg-gray-50">
                                        <i class="mr-2 fas fa-info-circle"></i> No se encontraron clientes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-8">
                    {{ $clients->links() }}
                </div>

            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{--🗑️ Modal de Confirmación Estilizado para Clientes (Tailwind/JS)
    {{--    Adaptado del modal de servicios para ser genérico para clientes.    --}}
    {{-- ========================================================= --}}

    <!-- Modal Overlay (fondo oscuro) -->
    <div id="deleteClientModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">

        <!-- Contenedor del Diálogo -->
        <div class="w-full max-w-md p-6 mx-4 transition-all duration-300 transform scale-95 bg-white rounded-lg shadow-2xl opacity-0" id="clientModalContent">

            <!-- Icono de Advertencia -->
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <i class="text-2xl text-red-600 fas fa-exclamation-triangle"></i>
            </div>

            <!-- Título y Mensaje -->
            <h3 class="mb-2 text-lg font-semibold text-center text-gray-900">Confirmar Eliminación de Cliente</h3>
            <p id="clientModalMessage" class="text-sm text-center text-gray-500">
                ¿Estás seguro de que quieres eliminar al cliente? Esto podría afectar a facturas existentes y la acción no se puede deshacer.
            </p>

            <!-- Área de Botones -->
            <div class="flex justify-center mt-6 space-x-4">
                <button
                    onclick="hideDeleteClientModal()"
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-gray-700 transition duration-150 bg-gray-200 rounded-lg shadow-sm hover:bg-gray-300">
                    Cancelar
                </button>
                <button
                    onclick="confirmClientDelete()"
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-white transition duration-150 bg-red-600 rounded-lg shadow-md hover:bg-red-700">
                    Sí, Eliminar Cliente
                </button>
            </div>

            <input type="hidden" id="clientFormToDelete">
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- 🧠 Script para Controlar el Modal de Clientes
    {{-- ========================================================= --}}

    <script>
        // Referencias al modal y elementos internos
        const clientModal = document.getElementById('deleteClientModal');
        const clientModalContent = document.getElementById('clientModalContent');
        const clientModalMessage = document.getElementById('clientModalMessage');
        const clientFormToDeleteInput = document.getElementById('clientFormToDelete');

        /**
         * Muestra el modal de eliminación de cliente.
         */
        function showDeleteClientModal(clientName, formId) {
            clientModal.classList.remove('hidden');
            clientModal.classList.add('flex');

            setTimeout(() => {
                clientModalContent.classList.remove('scale-95', 'opacity-0');
                clientModalContent.classList.add('scale-100', 'opacity-100');
            }, 50);

            clientModalMessage.innerHTML = `¿Estás seguro de que quieres eliminar al cliente: <strong>${clientName}</strong>? Esto podría afectar a facturas existentes y la acción no se puede deshacer.`;
            clientFormToDeleteInput.value = formId;
        }

        /**
         * Oculta el modal de eliminación de cliente.
         */
        function hideDeleteClientModal() {
            clientModalContent.classList.remove('scale-100', 'opacity-100');
            clientModalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                clientModal.classList.add('hidden');
                clientModal.classList.remove('flex');
            }, 300);
        }

        /**
         * Confirma la eliminación y envía el formulario.
         */
        function confirmClientDelete() {
            const formId = clientFormToDeleteInput.value;
            if (formId) {
                document.getElementById(formId).submit();
                hideDeleteClientModal();
            }
        }

        // Cierre del modal al hacer clic en el fondo oscuro
        clientModal.addEventListener('click', (e) => {
            if (e.target.id === 'deleteClientModal') {
                hideDeleteClientModal();
            }
        });

    </script>
</x-app-layout>
