@props(['service' => new App\Models\Service(), 'action' => 'store'])

<div class="p-8 bg-white border border-gray-100 shadow-2xl rounded-xl">

    {{-- Título y Descripción --}}
    <header class="pb-4 mb-8 border-b border-indigo-200">
        <h2 class="text-3xl font-extrabold text-gray-900">
            {{ $service->exists ? __('Editar Servicio') : __('Crear Nuevo Servicio') }}
        </h2>
        <p class="mt-2 text-base text-gray-500">
            <i class="mr-1 text-indigo-500 fas fa-info-circle"></i> {{ __('Ingrese los detalles completos del servicio, código, nombre, precio base y descripción.') }}
        </p>
    </header>

    {{-- Campos del Formulario --}}
    <div class="space-y-8">

        {{-- Código --}}
        <div>
            <x-input-label for="codigo" :value="__('Código')" class="mb-1 font-semibold text-gray-700" />
            <x-text-input
                id="codigo"
                name="codigo"
                type="text"
                class="w-full p-3 text-base placeholder-gray-400 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :value="old('codigo', $service->codigo)"
                placeholder="Ej. SVC004"
                required
                autofocus
            />
            <x-input-error class="mt-2" :messages="$errors->get('codigo')" />
        </div>

        {{-- Nombre del Servicio --}}
        <div>
            <x-input-label for="nombre_servicio" :value="__('Nombre del Servicio')" class="mb-1 font-semibold text-gray-700" />
            <x-text-input
                id="nombre_servicio"
                name="nombre_servicio"
                type="text"
                class="w-full p-3 text-base placeholder-gray-400 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                :value="old('nombre_servicio', $service->nombre_servicio)"
                required
            />
            <x-input-error class="mt-2" :messages="$errors->get('nombre_servicio')" />
        </div>

        {{-- Precio Base (Tamaño más compacto) --}}
        <div class="max-w-xs">
            <x-input-label for="precio" :value="__('Precio Base')" class="mb-1 font-semibold text-gray-700" />
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-lg font-bold text-gray-500">
                    {{ $setting->simbolo_moneda ?? 'S/' }}
                </span>
                <x-text-input
                    id="precio"
                    name="precio"
                    type="number"
                    step="0.01"
                    class="w-full py-3 pl-8 pr-3 text-lg font-bold text-gray-800 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :value="old('precio', $service->precio)"
                    placeholder="0.00"
                    required
                />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('precio')" />
        </div>

        {{-- Descripción --}}
        <div>
            <x-input-label for="descripcion" :value="__('Descripción (Opcional)')" class="mb-1 font-semibold text-gray-700" />
            <textarea
                id="descripcion"
                name="descripcion"
                rows="5" {{-- Aumentado a 5 filas para mejor visibilidad --}}
                class="w-full p-3 text-base placeholder-gray-400 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Detalles detallados sobre el servicio ofrecido, incluyendo alcance, límites y entregables."
            >{{ old('descripcion', $service->descripcion) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('descripcion')" />
        </div>
    </div>
</div>
