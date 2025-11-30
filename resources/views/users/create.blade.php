<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold leading-tight text-gray-800">{{ __('Crear Nuevo Usuario') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="p-8 bg-white shadow-2xl sm:rounded-xl">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    {{-- Incluye el formulario de campos --}}
                    @include('users.form')

                    {{-- Botones de Acción --}}
                    <div class="flex items-center justify-end gap-4 pt-6 mt-8 border-t border-gray-100">

                        {{-- Botón Principal: Guardar Usuario --}}
                        <x-primary-button class="px-6 py-3 text-base font-bold transition duration-150 ease-in-out bg-green-600 rounded-lg shadow-md hover:bg-green-700">
                            <i class="mr-2 fas fa-check-circle"></i> {{ __('Guardar Usuario') }}
                        </x-primary-button>

                        {{-- Botón Secundario: Cancelar --}}
                        <a href="{{ route('users.index') }}"
                           class="px-4 py-3 text-sm font-medium text-indigo-600 transition duration-150 ease-in-out border border-indigo-400 rounded-lg hover:bg-indigo-50 hover:border-indigo-500 hover:text-indigo-800">
                            <i class="mr-2 fas fa-times-circle"></i> {{ __('Cancelar') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
