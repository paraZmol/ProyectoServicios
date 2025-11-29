<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <style>
        .table-header {
            background-color: #f3f4f6;
        }

        .table-header th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-body td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #1f2937;
            border-top: 1px solid #e5e7eb;
        }
    </style>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">

                {{-- btn nuevo --}}
                <div class="flex justify-end mb-6">
                    <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 font-semibold text-white bg-indigo-600 rounded-md shadow-md hover:bg-indigo-700">
                        {{ __('Nuevo Usuario') }}
                    </a>
                </div>

                {{-- tabla de usuarios --}}
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6" scope="col">{{ __('Nombre') }}</th>
                            <th class="px-6" scope="col">{{ __('Email') }}</th>
                            <th class="px-6" scope="col">{{ __('Fecha de Creación') }}</th>
                            <th class="px-6 text-right" scope="col">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 table-body">
                        @foreach ($users as $user)
                            <tr>
                                <td class="whitespace-nowrap">{{ $user->name }}</td>
                                <td class="whitespace-nowrap">{{ $user->email }}</td>
                                <td class="whitespace-nowrap">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="text-right whitespace-nowrap">
                                    <a href="{{ route('users.edit', $user) }}" class="mr-3 text-indigo-600 hover:text-indigo-900">
                                        {{ __('Editar') }}
                                    </a>

                                    {{-- eleiminar --}}
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 focus:outline-none">
                                            {{ __('Eliminar') }}
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
