<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buscar Servicios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensaje de éxito/error (Flash Message) --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- FORMULARIO DE BÚSQUEDA Y BOTÓN NUEVO SERVICIO --}}
                <div class="flex justify-between items-center mb-6">
                    <form method="GET" action="{{ route('services.index') }}" class="flex items-center space-x-2 w-full">
                        <x-text-input type="text" name="search" placeholder="Código o nombre del servicio" value="{{ $search }}" class="w-full sm:w-80" />
                        <x-primary-button type="submit">
                            <i class="fa fa-search"></i> {{ __('Buscar') }}
                        </x-primary-button>
                    </form>
                    <a href="{{ route('services.create') }}" class="ml-4 bg-sky-500 hover:bg-sky-600 text-white font-bold py-2 px-4 rounded shadow-md whitespace-nowrap">
                        {{ __('Nuevo Servicio') }}
                    </a>
                </div>

                {{-- TABLA DE RESULTADOS --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-sky-100/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agregado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($services as $service)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->codigo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $service->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($service->precio, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $service->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end space-x-2">
                                        <a href="{{ route('services.edit', $service) }}" class="text-indigo-600 hover:text-indigo-900 p-2 border border-gray-300 rounded shadow-sm hover:shadow-md">
                                            <i class="fa fa-pencil-alt"></i> Editar
                                        </a>
                                        <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-2 border border-gray-300 rounded shadow-sm hover:shadow-md">
                                                <i class="fa fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No se encontraron servicios.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                <div class="mt-4">
                    {{ $services->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
