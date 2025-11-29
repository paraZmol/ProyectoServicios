<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ __('Crear Nuevo Usuario') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    @include('users.form')

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">{{ __('Guardar Usuario') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
