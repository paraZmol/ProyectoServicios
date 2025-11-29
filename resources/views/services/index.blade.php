<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Buscar Servicios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- mensaje de erro - exito --}}
            @if (session('success'))
                <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                {{-- form busqueda y nuevo --}}
                <div class="flex items-center justify-between mb-6">
                    <form method="GET" action="{{ route('services.index') }}" class="flex items-center w-full space-x-2">
                        <x-text-input type="text" name="search" placeholder="Código o nombre del servicio" value="{{ $search }}" class="w-full sm:w-80" />
                        <x-primary-button type="submit">
                            <i class="fa fa-search"></i> {{ __('Buscar') }}
                        </x-primary-button>
                    </form>
                    <a href="{{ route('services.create') }}" class="px-4 py-2 ml-4 font-bold text-white bg-indigo-600 rounded shadow-md hover:bg-indigo-900 whitespace-nowrap">
                        {{ __('Nuevo Servicio') }}
                    </a>
                </div>

                {{-- tabla de resutlado --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-sky-100/50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Código</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Servicio</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Precio</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Agregado</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($services as $service)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $service->codigo }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $service->nombre_servicio }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">${{ number_format($service->precio, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $service->created_at->format('d/m/Y') }}</td>
                                    <td class="flex justify-end px-6 py-4 space-x-2 text-sm font-medium text-center whitespace-nowrap">
                                        <a href="{{ route('services.edit', $service) }}" class="p-2 text-indigo-600 border border-gray-300 rounded shadow-sm hover:text-indigo-900 hover:shadow-md">
                                            <i class="fa-light fa-pen-to-square"></i> Editar
                                        </a>
                                        <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 border border-gray-300 rounded shadow-sm hover:text-red-900 hover:shadow-md">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">
                                        No se encontraron servicios.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- paginacion --}}
                <div class="mt-4">
                    {{ $services->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
