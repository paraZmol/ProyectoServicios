<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold leading-tight text-gray-800">
            {{ __('🗑️ Papelera de Servicios Eliminados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            {{-- mensaje --}}
            @if (session('success'))
                <div class="p-4 mb-6 font-medium text-white bg-green-500 rounded-lg shadow-md" role="alert">
                    <p class="flex items-center"><i class="mr-2 fas fa-check-circle"></i> {{ session('success') }}</p>
                </div>
            @endif

            <div class="p-8 overflow-hidden bg-white shadow-2xl sm:rounded-xl">

                <div class="mb-8">
                    <p class="text-gray-600">Mostrando servicios marcados como eliminados. Solo los administradores pueden ver esta lista y restaurar los registros.</p>
                </div>

                <hr class="mb-6 border-gray-100">

                {{-- tabla de resultados --}}
                <div class="overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-100/70">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-[#1E3A8A] uppercase">Código</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-[#1E3A8A] uppercase">Servicio</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-[#1E3A8A] uppercase">Precio</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-red-700 uppercase">Eliminado el</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-[#1E3A8A] uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($services as $service)
                                <tr class="transition duration-100 ease-in-out hover:bg-red-50">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-800 whitespace-nowrap">{{ $service->codigo }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $service->nombre_servicio }}</td>

                                    {{-- precios --}}
                                    <td class="px-6 py-4 text-sm font-bold text-[#059669] whitespace-nowrap">
                                        {{-- asumo que setting viene del controller, si no, usar helper --}}
                                        {{ isset($setting->simbolo_moneda) ? $setting->simbolo_moneda : 'S/' }} {{ number_format($service->precio, 2) }}
                                    </td>

                                    {{-- fecha --}}
                                    <td class="px-6 py-4 text-sm text-center text-red-600">
                                        {{ $service->deleted_at ? \Carbon\Carbon::parse($service->deleted_at)->format('d/m/Y h:i A') : 'N/A' }}
                                    </td>

                                    {{-- acciones --}}
                                    <td class="flex justify-center px-6 py-4 space-x-3 text-sm font-medium whitespace-nowrap">
                                        {{-- restaurar --}}
                                        <form action="{{ route('services.restore', $service->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="flex items-center p-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-[#0BA976] rounded-full shadow-sm hover:bg-green-600"
                                                title="Restaurar Servicio">
                                                <i class="mr-1 fas fa-undo"></i> Restaurar
                                            </button>
                                        </form>

                                        {{-- eliminar permanentemente modal --}}
                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('services.forceDelete', $service->id) }}')"
                                            class="flex items-center p-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-[#EC4040] rounded-lg shadow-sm hover:bg-[#DD2828]"
                                            title="Eliminar permanentemente">
                                            <i class="mr-1 fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-base text-center text-gray-500 bg-gray-50">
                                        <i class="mr-2 fas fa-box-open"></i> No hay servicios eliminados en la papelera.
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

    {{-- MODAL PERSONALIZADO DE ELIMINACIÓN --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Fondo oscurecido con efecto blur -->
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" aria-hidden="true" onclick="closeDeleteModal()"></div>

            <!-- Truco para centrar el modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Contenido del Modal -->
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white border border-red-100 shadow-2xl rounded-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <!-- Icono de Advertencia Animado -->
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10 animate-pulse">
                            <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">
                                Eliminación Permanente
                            </h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-500">
                                    ¿Estás seguro de que deseas eliminar permanentemente este servicio?
                                </p>
                                <div class="p-3 mt-4 border-l-4 border-red-500 rounded-lg bg-red-50">
                                    <p class="text-xs font-semibold text-red-700">
                                        ⚠️ IMPORTANTE: IRREVERSIBLE
                                    </p>
                                    <p class="mt-1 text-xs text-red-600">
                                        Si continúas, las boletas históricas que usen este servicio perderán la referencia original, aunque intentaremos desvincularlas de forma segura.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Botones del Footer -->
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white transition-colors duration-200 bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Sí, Eliminar Definitivamente
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2C326E] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS PARA EL MODAL --}}
    <script>
        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeDeleteModal();
            }
        });
    </script>
</x-app-layout>
