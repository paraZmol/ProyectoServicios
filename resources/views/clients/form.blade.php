@props(['client' => new App\Models\Client()])

{{-- Título y Descripción (Usando un header estilizado para consistencia) --}}
<header class="pb-4 mb-8 border-b border-blue-200">
    <h2 class="text-3xl font-extrabold text-gray-900">
        {{ $client->exists ? __('Editar Cliente') : __('Crear Nuevo Cliente') }}
    </h2>
    <p class="mt-2 text-base text-gray-500">
        <i class="mr-1 text-blue-500 fas fa-user"></i> {{ __('Complete la información del cliente, incluyendo datos de identificación y contacto.') }}
    </p>
</header>

<div class="space-y-6">

    {{-- Campo Principal: Nombre del Cliente (Ancho Completo) --}}
    <div>
        <x-input-label for="nombre" :value="__('Nombre Completo / Razon Social')" class="mb-1 font-semibold text-gray-700" />
        <x-text-input
            id="nombre"
            name="nombre"
            type="text"
            class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            :value="old('nombre', $client->nombre)"
            required
            autofocus
        />
        <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
    </div>

    {{-- GRUPO DE 2 COLUMNAS: Identificación y Contacto --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        {{-- DNI --}}
        <div>
            <x-input-label for="dni" :value="__('DNI / RUC')" class="mb-1 font-semibold text-gray-700" />
            <x-text-input
                id="dni"
                name="dni"
                type="text"
                class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :value="old('dni', $client->dni)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('dni')" />
        </div>

        {{-- Teléfono --}}
        <div>
            <x-input-label for="telefono" :value="__('Teléfono (Opcional)')" class="mb-1 font-semibold text-gray-700" />
            <x-text-input
                id="telefono"
                name="telefono"
                type="text"
                class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :value="old('telefono', $client->telefono)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
        </div>

    </div>

    {{-- GRUPO DE 2 COLUMNAS: Email y Estado --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        {{-- Correo Electrónico --}}
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico (Opcional)')" class="mb-1 font-semibold text-gray-700" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :value="old('email', $client->email)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        {{-- Estado del Cliente (Select estilizado) --}}
        <div>
            <x-input-label for="estado" :value="__('Estado')" class="mb-1 font-semibold text-gray-700" />
            <select
                id="estado"
                name="estado"
                class="w-full p-3 text-base transition duration-150 ease-in-out border-gray-300 rounded-lg shadow-sm appearance-none focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="" @if(old('estado', $client->estado) === null) selected @endif>-- Sin especificar (Nulo) --</option>
                <option value="activo" @if(old('estado', $client->estado) === 'activo') selected @endif>Activo</option>
                <option value="inactivo" @if(old('estado', $client->estado) === 'inactivo') selected @endif>Inactivo</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('estado')" />
        </div>

    </div>

    {{-- Dirección (Ancho Completo) --}}
    <div>
        <x-input-label for="direccion" :value="__('Dirección (Opcional)')" class="mb-1 font-semibold text-gray-700" />
        <x-text-input
            id="direccion"
            name="direccion"
            type="text"
            class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            :value="old('direccion', $client->direccion)"
        />
        <x-input-error class="mt-2" :messages="$errors->get('direccion')" />
    </div>

</div>
