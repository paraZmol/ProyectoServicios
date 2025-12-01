<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold leading-tight text-gray-800">
            {{ __('🗑️ Papelera de Clientes Eliminados') }}
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
                    <p class="text-gray-600">Mostrando clientes marcados como eliminados. Solo los administradores pueden ver esta lista y restaurar los registros.</p>
                </div>

                <hr class="mb-6 border-gray-100">

                {{-- tabla de resultados --}}
                <div class="overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-100/70">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Nombre / Documento</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-700 uppercase">Contacto</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-red-700 uppercase">Eliminado el</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-blue-700 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($clients as $client)
                                <tr class="transition duration-100 ease-in-out hover:bg-red-50">
                                    {{-- nombre y document --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $client->nombre }}</div>
                                        @php
                                            // logica para el tipo de documento
                                            $getDocumentType = function ($documento) {
                                                if (empty($documento)) return ['etiqueta' => 'Documento'];
                                                $length = strlen(preg_replace('/[^0-9A-Za-z]/', '', $documento));
                                                if ($length === 8) return ['etiqueta' => 'DNI'];
                                                if ($length === 11) return ['etiqueta' => 'RUC'];
                                                return ['etiqueta' => 'DI / Otro'];
                                            };
                                            $docInfo = $getDocumentType($client->dni);
                                        @endphp
                                        <div class="text-xs text-gray-500">
                                            <span class="font-semibold text-blue-600">{{ $docInfo['etiqueta'] }}:</span> {{ $client->dni ?? 'N/A' }}
                                        </div>
                                    </td>

                                    {{-- contato --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-800 whitespace-nowrap"><i class="mr-1 text-gray-400 fas fa-envelope"></i> {{ $client->email ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500 whitespace-nowrap"><i class="mr-1 text-gray-400 fas fa-phone"></i> {{ $client->telefono ?? 'N/A' }}</div>
                                    </td>

                                    {{-- fecha --}}
                                    <td class="px-6 py-4 text-sm text-center text-red-600">
                                        {{ $client->deleted_at ? \Carbon\Carbon::parse($client->deleted_at)->format('d/m/Y h:i A') : 'N/A' }}
                                    </td>

                                    {{-- accion --}}
                                    <td class="flex justify-center px-6 py-4 space-x-3 text-sm font-medium whitespace-nowrap">

                                        {{-- restaurar --}}
                                        <form action="{{ route('clients.restore', $client->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="flex items-center p-2 text-sm font-medium text-green-700 transition duration-150 ease-in-out bg-green-100 rounded-full shadow-sm hover:bg-green-200">
                                                <i class="mr-1 fas fa-undo"></i> Restaurar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-base text-center text-gray-500 bg-gray-50">
                                        <i class="mr-2 fas fa-box-open"></i> No hay clientes eliminados en la papelera.
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
</x-app-layout>
