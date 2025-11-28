@props(['service' => new App\Models\Service(), 'action' => 'store'])

<style>
    .form-container {
        padding: 1.5rem;
        background-color: white;
        border-radius: 0.5rem;
    }

    .divider {
        border-bottom: 1px solid #e5e7eb;
    }

    .form-field-control {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.15s ease-in-out;
    }

    .form-field-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 1px #4f46e5;
    }

    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
</style>

<div class="form-container">

    {{-- titulo y descripcion --}}
    <header class="pb-4 mb-6 divider">
        <h2 class="text-2xl font-bold text-gray-900" style="font-size: 1.5rem; font-weight: bold; color: #1f2937;">
            {{ $service->exists ? __('Editar Servicio') : __('Crear Nuevo Servicio') }}
        </h2>
        <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #6b7280;">
            {{ __('Ingrese los detalles del servicio.') }}
        </p>
    </header>

    {{-- campos --}}
    <div class="space-y-6">

        {{-- codigo --}}
        <div>
            <x-input-label for="codigo" :value="__('Código')" />
            <x-text-input
                id="codigo"
                name="codigo"
                type="text"
                class="form-field-control"
                :value="old('codigo', $service->codigo)"
                placeholder="Ej. SVC004"
                required
                autofocus
            />
            <x-input-error class="mt-2" :messages="$errors->get('codigo')" />
        </div>

        {{-- nombre del servicio --}}
        <div>
            <x-input-label for="nombre_servicio" :value="__('Nombre del Servicio')" />
            <x-text-input
                id="nombre_servicio"
                name="nombre_servicio"
                type="text"
                class="form-field-control"
                :value="old('nombre_servicio', $service->nombre_servicio)"
                required
            />
            <x-input-error class="mt-2" :messages="$errors->get('nombre_servicio')" />
        </div>

        {{-- precio base --}}
        <div>
            <x-input-label for="precio" :value="__('Precio Base')" />
            <x-text-input
                id="precio"
                name="precio"
                type="number"
                step="0.01"
                class="form-field-control"
                :value="old('precio', $service->precio)"
                placeholder="0.00"
                required
            />
            <x-input-error class="mt-2" :messages="$errors->get('precio')" />
        </div>

        {{-- descripcion --}}
        <div>
            <x-input-label for="descripcion" :value="__('Descripción (Opcional)')" />
            <textarea
                id="descripcion"
                name="descripcion"
                rows="3"
                class="form-field-control"
                placeholder="Detalles sobre el servicio ofrecido."
            >{{ old('descripcion', $service->descripcion) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('descripcion')" />
        </div>
    </div>
</div>
