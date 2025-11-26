@props(['service' => new App\Models\Service()])

<div class="space-y-6">
    <div>
        <x-input-label for="codigo" :value="__('Código')" />
        <x-text-input id="codigo" name="codigo" type="text" class="mt-1 block w-full" :value="old('codigo', $service->codigo)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('codigo')" />
    </div>

    <div>
        <x-input-label for="nombre" :value="__('Nombre del Servicio')" />
        <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $service->nombre)" required />
        <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
    </div>

    <div>
        <x-input-label for="precio" :value="__('Precio Base')" />
        {{-- Usamos 'number' para asegurar el formato decimal --}}
        <x-text-input id="precio" name="precio" type="number" step="0.01" class="mt-1 block w-full" :value="old('precio', $service->precio)" required />
        <x-input-error class="mt-2" :messages="$errors->get('precio')" />
    </div>

    <div>
        <x-input-label for="descripcion" :value="__('Descripción (Opcional)')" />
        <textarea id="descripcion" name="descripcion" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descripcion', $service->descripcion) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('descripcion')" />
    </div>
</div>
