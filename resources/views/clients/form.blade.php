{{-- resources/views/clients/form.blade.php --}}

@props(['client' => new App\Models\Client()])

<div class="space-y-6">
    <div>
        <x-input-label for="nombre" :value="__('Nombre del Cliente')" />
        <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $client->nombre)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
    </div>

    <div>
        <x-input-label for="telefono" :value="__('Teléfono (Opcional)')" />
        <x-text-input id="telefono" name="telefono" type="text" class="mt-1 block w-full" :value="old('telefono', $client->telefono)" />
        <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
    </div>

    <div>
        <x-input-label for="email" :value="__('Correo Electrónico (Opcional)')" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $client->email)" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="direccion" :value="__('Dirección (Opcional)')" />
        <x-text-input id="direccion" name="direccion" type="text" class="mt-1 block w-full" :value="old('direccion', $client->direccion)" />
        <x-input-error class="mt-2" :messages="$errors->get('direccion')" />
    </div>

    {{-- Campo para el estado del cliente (Ajuste por ser nullable) --}}
    <div>
        <x-input-label for="estado" :value="__('Estado')" />
        <select id="estado" name="estado" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="" @if(old('estado', $client->estado) === null) selected @endif>-- Sin especificar (Nulo) --</option>
            <option value="activo" @if(old('estado', $client->estado) === 'activo') selected @endif>Activo</option>
            <option value="inactivo" @if(old('estado', $client->estado) === 'inactivo') selected @endif>Inactivo</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('estado')" />
    </div>

</div>
