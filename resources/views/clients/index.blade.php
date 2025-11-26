<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Buscar Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                {{-- formulario de busqueda y el boton cliente --}}
                <div class="flex items-center justify-between mb-6">
                    <form method="GET" action="{{ route('clients.index') }}" class="flex items-center w-full space-x-2">
                        <x-text-input type="text" name="search" placeholder="Nombre o email del cliente" value="{{ $search }}" class="w-full sm:w-80" />
                        <x-primary-button type="submit">
                            {{ __('Buscar') }}
                        </x-primary-button>
                    </form>
                    <a href="{{ route('clients.create') }}" class="px-4 py-2 ml-4 font-bold text-white rounded shadow-md bg-sky-500 hover:bg-sky-600 whitespace-nowrap">
                        {{ __('Nuevo Cliente') }}
                    </a>
                </div>

                {{-- table resutlados --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-sky-100/50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Teléfono</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Dirección</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($clients as $client)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $client->nombre }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $client->telefono ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $client->email ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $client->direccion ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-{{ $client->estado === 'activo' ? 'green' : ($client->estado === 'inactivo' ? 'red' : 'gray') }}-600">
                                        {{ ucfirst($client->estado ?? 'N/A') }}
                                    </td>
                                    <td class="flex justify-end px-6 py-4 space-x-2 text-sm font-medium text-right whitespace-nowrap">
                                        <a href="{{ route('clients.edit', $client) }}" class="p-2 text-indigo-600 border border-gray-300 rounded shadow-sm hover:text-indigo-900 hover:shadow-md">
                                            Editar
                                        </a>
                                        <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este cliente? Esto podría afectar a facturas existentes.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 border border-gray-300 rounded shadow-sm hover:text-red-900 hover:shadow-md">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">
                                        No se encontraron clientes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- paginacion --}}
                <div class="mt-4">
                    {{ $clients->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
