<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">

                {{-- btn nuevo --}}
                <div class="flex justify-end mb-6">
                    <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 font-semibold text-white bg-indigo-600 rounded-md shadow-md hover:bg-indigo-700">
                        <i class="mr-1 fa fa-plus-circle"></i>{{ __('Nuevo Usuario') }}
                    </a>
                </div>

                {{-- tabla de usuarios --}}
                    <table class="min-w-full table-auto">
                        <thead class="bg-sky-100/50">
                            <tr>
                                <th class="px-6 py-3 tracking-wider uppercase">Usuario</th>
                                <th class="px-6 tracking-wider uppercase">Email</th>
                                <th class="px-6 tracking-wider uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="flex justify-end px-6 py-4 space-x-2 whitespace-nowrap">
                                        {{-- editar --}}
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="p-2 text-yellow-600 border border-gray-300 rounded shadow-sm hover:bg-yellow-100 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>Editar
                                        </a>

                                        {{-- eleiminar --}}
                                        <form action="{{ route('users.destroy', $user) }}"
                                            method="POST"
                                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="p-2 text-red-600 border border-gray-300 rounded shadow-sm hover:bg-red-100 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                {{-- paginacion --}}
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
