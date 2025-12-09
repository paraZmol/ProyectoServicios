<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold leading-tight text-gray-800">
            {{ __('Editar Servicio: ') }}<span class="text-[#253891]">{{ $service->nombre_servicio }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow-2xl sm:rounded-xl">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('services.update', $service) }}" class="mt-6 space-y-8">
                        @csrf
                        @method('PUT')

                        {{-- Incluye el formulario de campos con los estilos geniales --}}
                        @include('services.form', ['service' => $service])

                        {{-- Botones de Acción --}}
                        <div class="flex items-center gap-4 pt-6 mt-8 border-t border-gray-100">

                            {{-- Botón Principal: Actualizar --}}
                            <x-primary-button class="px-6 py-3 text-base font-bold transition duration-150 ease-in-out bg-[#253891] rounded-lg shadow-md hover:bg-[#2C326E]">
                                <i class="mr-2 fas fa-save"></i> {{ __('Actualizar Servicio') }}
                            </x-primary-button>

                            {{-- Botón Secundario: Cancelar --}}
                            <a href="{{ route('services.index') }}"
                               class="px-4 py-3 text-sm font-medium text-[#253891] transition duration-150 ease-in-out border border-indigo-400 rounded-lg hover:bg-indigo-50 hover:border-indigo-500 hover:text-indigo-800">
                                <i class="mr-2 fas fa-times-circle"></i> {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
