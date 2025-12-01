<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight text-gray-800">
            {{ __('Gestión de Servicios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Mensaje de éxito/error (Estilo moderno) --}}
            @if (session('success'))
                <div class="p-4 mb-6 font-medium text-white bg-green-500 rounded-lg shadow-md" role="alert">
                    <p class="flex items-center"><i class="mr-2 fas fa-check-circle"></i> {{ session('success') }}</p>
                </div>
            @endif

            <div class="p-8 overflow-hidden bg-white shadow-2xl sm:rounded-xl">

                {{-- FORMULARIO DE BÚSQUEDA Y BOTÓN DE ACCIÓN --}}
                <div class="flex flex-col items-start justify-between mb-8 md:flex-row md:items-center">

                    {{-- Formulario de Búsqueda (Se mantiene w-96) --}}
                    <form method="GET" action="{{ route('services.index') }}" class="flex items-center w-full mb-4 space-x-3 md:mb-0 md:w-auto">
                        <x-text-input
                            type="text"
                            name="search"
                            placeholder="Código o nombre del servicio"
                            value="{{ $search }}"
                            class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 md:w-96" />

                        <button type="submit" class="flex items-center px-4 py-2 font-bold text-white transition duration-150 ease-in-out bg-gray-800 rounded-lg shadow-md hover:bg-gray-900">
                             <i class="mr-1 fas fa-search"></i> {{ __('Buscar') }}
                        </button>
                    </form>

                    {{-- Botón Nuevo Servicio --}}
                    @if (Auth::user()->role !=='usuario')
                        <a href="{{ route('services.create') }}"
                           class="flex items-center px-5 py-2 font-bold text-white transition duration-150 ease-in-out bg-indigo-600 rounded-lg shadow-lg hover:bg-indigo-700 whitespace-nowrap">
                            <i class="mr-2 fas fa-plus-circle"></i>{{ __('Nuevo Servicio') }}
                        </a>
                    @endif

                </div>

                <hr class="mb-6 border-gray-100">

                {{-- TABLA DE RESULTADOS --}}
                <div class="overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-100/70">
                            <tr>
                                @if (Auth::user()->role !=='usuario')
                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Código</th>
                                @endif
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Servicio</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Precio</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Descripción</th>
                                @if (Auth::user()->role !=='usuario')
                                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($services as $service)
                                <tr class="transition duration-100 ease-in-out hover:bg-gray-50">
                                    @if (Auth::user()->role !=='usuario')
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-800 whitespace-nowrap">{{ $service->codigo }}</td>
                                    @endif
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $service->nombre_servicio }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-green-600 whitespace-nowrap">
                                        {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($service->precio, 2) }}
                                    </td>
                                    <td class="max-w-xs px-6 py-4 text-sm text-gray-500">{{ $service->descripcion }}</td>
                                    @if (Auth::user()->role !=='usuario')
                                        <td class="flex justify-center px-6 py-4 space-x-3 text-sm font-medium whitespace-nowrap">

                                            {{-- Botón Editar --}}
                                            <a href="{{ route('services.edit', $service) }}"
                                                class="flex items-center p-2 text-sm font-medium text-yellow-700 transition duration-150 ease-in-out bg-yellow-100 rounded-full shadow-sm hover:bg-yellow-200">
                                                <i class="mr-1 fas fa-edit"></i> Editar
                                            </a>

                                            {{-- Formulario Eliminar: AHORA ACTIVA EL MODAL --}}
                                            <form id="delete-form-{{ $service->id }}"
                                                  action="{{ route('services.destroy', $service) }}"
                                                  method="POST"
                                                  onsubmit="return false;"> {{-- Importante: previene el submit normal --}}
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="button" {{-- Importante: type="button" para activar JS --}}
                                                    onclick="showDeleteModal('{{ $service->nombre_servicio }}', 'delete-form-{{ $service->id }}')"
                                                    class="flex items-center p-2 text-sm font-medium text-red-700 transition duration-150 ease-in-out bg-red-100 rounded-full shadow-sm hover:bg-red-200">
                                                    <i class="mr-1 fas fa-trash"></i> Eliminar
                                                </button>
                                            </form>

                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-base text-center text-gray-500 bg-gray-50">
                                        <i class="mr-2 fas fa-info-circle"></i> No se encontraron servicios que coincidan con la búsqueda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-8">
                    {{ $services->links() }}
                </div>

            </div>
        </div>
    </div>


    {{-- modal de confirmacion de borrado --}}

    <div id="deleteModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">

        <div class="w-full max-w-md p-6 mx-4 transition-all duration-300 transform scale-95 bg-white rounded-lg shadow-2xl opacity-0" id="modalContent">

            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <i class="text-2xl text-red-600 fas fa-exclamation-triangle"></i>
            </div>

            <h3 class="mb-2 text-lg font-semibold text-center text-gray-900">Confirmar Eliminación</h3>
            <p id="modalMessage" class="text-sm text-center text-gray-500">
                ¿Estás seguro de que quieres eliminar el servicio? Esta acción no se puede deshacer.
            </p>

            <div class="flex justify-center mt-6 space-x-4">
                <button
                    onclick="hideDeleteModal()"
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-gray-700 transition duration-150 bg-gray-200 rounded-lg shadow-sm hover:bg-gray-300">
                    Cancelar
                </button>
                <button
                    onclick="confirmDelete()"
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-white transition duration-150 bg-red-600 rounded-lg shadow-md hover:bg-red-700">
                    Sí, Eliminar
                </button>
            </div>

            <input type="hidden" id="formToDelete">
        </div>
    </div>


    {{-- controlar modal --}}
    <script>
        // Referencias al modal y elementos internos
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('modalContent');
        const modalMessage = document.getElementById('modalMessage');
        const formToDeleteInput = document.getElementById('formToDelete');

        /**
         * Muestra el modal y establece el contexto de eliminación.
         * @param {string} serviceName - Nombre del servicio a eliminar.
         * @param {string} formId - ID del formulario que debe ser enviado.
         */
        function showDeleteModal(serviceName, formId) {
            // 1. Mostrar el modal con estilos de transición
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // 2. Aplicar transición de entrada
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);

            // 3. Actualizar el mensaje y la referencia del formulario
            modalMessage.innerHTML = `¿Estás seguro de que quieres eliminar el servicio: <strong>${serviceName}</strong>? Esta acción no se puede deshacer.`;
            formToDeleteInput.value = formId;
        }

        /**
         * Oculta el modal y reinicia los estilos de transición.
         */
        function hideDeleteModal() {
            // Aplicar transición de salida
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            // Ocultar el modal después de la transición
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300); // 300ms debe coincidir con la duración de la transición en Tailwind
        }

        /**
         * Confirma la eliminación, envía el formulario y oculta el modal.
         */
        function confirmDelete() {
            const formId = formToDeleteInput.value;
            if (formId) {
                document.getElementById(formId).submit();
                hideDeleteModal();
            }
        }

        // Cierre del modal al hacer clic en el fondo oscuro
        modal.addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') {
                hideDeleteModal();
            }
        });

    </script>

</x-app-layout>
