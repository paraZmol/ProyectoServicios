{{-- resources/views/clients/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buscar Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- FORMULARIO DE BÚSQUEDA Y BOTÓN NUEVO CLIENTE --}}
                <div class="flex justify-between items-center mb-6">
                    <form method="GET" action="{{ route('clients.index') }}" class="flex items-center space-x-2 w-full">
                        <x-text-input type="text" name="search" placeholder="Nombre o email del cliente" value="{{ $search }}" class="w-full sm:w-80" />
                        <x-primary-button type="submit">
                            {{ __('Buscar') }}
                        </x-primary-button>
                    </form>
                    <a href="{{ route('clients.create') }}" class="ml-4 bg-sky-500 hover:bg-sky-600 text-white font-bold py-2 px-4 rounded shadow-md whitespace-nowrap">
                        {{ __('Nuevo Cliente') }}
                    </a>
                </div>

                {{-- TABLA DE RESULTADOS --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-sky-100/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($clients as $client)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->telefono ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->email ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->direccion ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-{{ $client->estado === 'activo' ? 'green' : ($client->estado === 'inactivo' ? 'red' : 'gray') }}-600">
                                        {{ ucfirst($client->estado ?? 'N/A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end space-x-2">
                                        <a href="{{ route('clients.edit', $client) }}" class="text-indigo-600 hover:text-indigo-900 p-2 border border-gray-300 rounded shadow-sm hover:shadow-md">
                                            Editar
                                        </a>
                                        <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este cliente? Esto podría afectar a facturas existentes.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-2 border border-gray-300 rounded shadow-sm hover:shadow-md">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No se encontraron clientes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                <div class="mt-4">
                    {{ $clients->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
