<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Crear Nuevo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('clients.store') }}" class="mt-6 space-y-6">
                        @csrf

                        @include('clients.form')

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Guardar Cliente') }}</x-primary-button>
                            <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-900">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
