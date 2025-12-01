<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold leading-tight text-gray-800">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-8 overflow-hidden bg-white shadow-2xl sm:rounded-xl">

                {{-- FORMULARIO DE BÚSQUEDA Y BOTÓN DE ACCIÓN --}}
                <div class="flex flex-col items-start justify-between mb-8 md:flex-row md:items-center">

                    {{-- Formulario de Búsqueda (Asumimos que $search se pasa desde el controlador) --}}
                    <form method="GET" action="{{ route('users.index') }}" class="flex items-center w-full mb-4 space-x-3 md:mb-0 md:w-auto">
                        {{-- **NOTA:** La variable $search debe ser definida en el controlador --}}
                        <x-text-input
                            type="text"
                            name="search"
                            placeholder="Buscar por usuario o correo"
                            value="{{ $search ?? '' }}"
                            class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 md:w-96" />

                        <x-primary-button type="submit" class="flex items-center px-4 py-2 font-bold transition duration-150 ease-in-out bg-gray-800 rounded-lg shadow-md hover:bg-gray-900">
                             <i class="mr-1 fas fa-search"></i> {{ __('Buscar') }}
                        </x-primary-button>
                    </form>

                    {{-- Botón Nuevo Usuario --}}
                    <a href="{{ route('users.create') }}"
                       class="flex items-center px-5 py-2 font-bold text-white transition duration-150 ease-in-out bg-indigo-600 rounded-lg shadow-lg hover:bg-indigo-700 whitespace-nowrap">
                        <i class="mr-2 fas fa-plus-circle"></i>{{ __('Nuevo Usuario') }}
                    </a>
                </div>

                <hr class="mb-6 border-gray-100">

                {{-- TABLA DE USUARIOS --}}
                <div class="overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full divide-y divide-gray-200 table-auto">
                        <thead class="bg-blue-100/70">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Usuario</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Email</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Rol</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($users as $user)
                                <tr class="transition duration-100 ease-in-out hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $user->email }}</td>

                                    {{-- Columna de Rol (Espacio Reservado) --}}
                                    <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                                        {{-- COMENTARIO: Usar lógica aquí para mostrar el rol con un badge si existe la propiedad, ej: $user->role --}}
                                        <span class="inline-flex px-3 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                            {{ $user->role }}
                                        </span>
                                    </td>

                                    <td class="flex justify-center px-6 py-4 space-x-3 text-sm font-medium whitespace-nowrap">
                                        {{-- editar --}}
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="flex items-center p-2 text-sm font-medium text-yellow-700 transition duration-150 ease-in-out bg-yellow-100 rounded-full shadow-sm hover:bg-yellow-200">
                                            <i class="mr-1 fas fa-edit"></i>Editar
                                        </a>

                                        {{-- eleiminar (Activa el Modal) --}}
                                        <form id="delete-user-form-{{ $user->id }}"
                                            action="{{ route('users.destroy', $user) }}"
                                            method="POST"
                                            onsubmit="return false;">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                onclick="showDeleteUserModal('{{ $user->name }}', 'delete-user-form-{{ $user->id }}')"
                                                class="flex items-center p-2 text-sm font-medium text-red-700 transition duration-150 ease-in-out bg-red-100 rounded-full shadow-sm hover:bg-red-200">
                                                <i class="mr-1 fas fa-trash"></i>Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-base text-center text-gray-500 bg-gray-50">
                                        <i class="mr-2 fas fa-info-circle"></i> No se encontraron usuarios.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- paginacion --}}
                <div class="mt-8">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- 🗑️ Modal de Confirmación Estilizado para Usuarios
    {{-- ========================================================= --}}

    <!-- Modal Overlay (fondo oscuro) -->
    <div id="deleteUserModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">

        <!-- Contenedor del Diálogo -->
        <div class="w-full max-w-md p-6 mx-4 transition-all duration-300 transform scale-95 bg-white rounded-lg shadow-2xl opacity-0" id="userModalContent">

            <!-- Icono de Advertencia -->
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <i class="text-2xl text-red-600 fas fa-exclamation-triangle"></i>
            </div>

            <!-- Título y Mensaje -->
            <h3 class="mb-2 text-lg font-semibold text-center text-gray-900">Confirmar Eliminación de Usuario</h3>
            <p id="userModalMessage" class="text-sm text-center text-gray-500">
                ¿Estás seguro de que quieres eliminar a este usuario? Esta acción es irreversible.
            </p>

            <!-- Área de Botones -->
            <div class="flex justify-center mt-6 space-x-4">
                <button
                    onclick="hideDeleteUserModal()"
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-gray-700 transition duration-150 bg-gray-200 rounded-lg shadow-sm hover:bg-gray-300">
                    Cancelar
                </button>
                <button
                    onclick="confirmUserDelete()"
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-white transition duration-150 bg-red-600 rounded-lg shadow-md hover:bg-red-700">
                    Sí, Eliminar Usuario
                </button>
            </div>

            <input type="hidden" id="userFormToDelete">
        </div>
    </div>


    {{-- ========================================================= --}}
    {{--🧠 Script para Controlar el Modal de Usuarios
    {{-- ========================================================= --}}

    <script>
        // Referencias al modal y elementos internos
        const userModal = document.getElementById('deleteUserModal');
        const userModalContent = document.getElementById('userModalContent');
        const userModalMessage = document.getElementById('userModalMessage');
        const userFormToDeleteInput = document.getElementById('userFormToDelete');

        /**
         * Muestra el modal de eliminación de usuario.
         */
        function showDeleteUserModal(userName, formId) {
            userModal.classList.remove('hidden');
            userModal.classList.add('flex');

            setTimeout(() => {
                userModalContent.classList.remove('scale-95', 'opacity-0');
                userModalContent.classList.add('scale-100', 'opacity-100');
            }, 50);

            userModalMessage.innerHTML = `¿Estás seguro de que quieres eliminar al usuario: <strong>${userName}</strong>? Esta acción es irreversible.`;
            userFormToDeleteInput.value = formId;
        }

        /**
         * Oculta el modal de eliminación de usuario.
         */
        function hideDeleteUserModal() {
            userModalContent.classList.remove('scale-100', 'opacity-100');
            userModalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                userModal.classList.add('hidden');
                userModal.classList.remove('flex');
            }, 300);
        }

        /**
         * Confirma la eliminación y envía el formulario.
         */
        function confirmUserDelete() {
            const formId = userFormToDeleteInput.value;
            if (formId) {
                document.getElementById(formId).submit();
                hideDeleteUserModal();
            }
        }

        // Cierre del modal al hacer clic en el fondo oscuro
        userModal.addEventListener('click', (e) => {
            if (e.target.id === 'deleteUserModal') {
                hideDeleteUserModal();
            }
        });

    </script>
</x-app-layout>
