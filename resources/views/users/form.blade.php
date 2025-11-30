@props(['user' => new App\Models\User()])

{{-- Título y Descripción (Estilo moderno con color púrpura/morado para usuarios) --}}
<header class="pb-4 mb-8 border-b border-purple-200">
    <h2 class="text-3xl font-extrabold text-gray-900">
        {{ $user->exists ? __('Editar Usuario') : __('Crear Nuevo Usuario') }}
    </h2>
    <p class="mt-2 text-base text-gray-500">
        <i class="mr-1 text-purple-600 fas fa-user-shield"></i> {{ __('Complete los datos del usuario. La contraseña es requerida para nuevos usuarios.') }}
    </p>
</header>

<div class="space-y-6">

    {{-- Nombre --}}
    <div>
        <x-input-label for="name" :value="__('Nombre')" class="mb-1 font-semibold text-gray-700" />
        <x-text-input
            id="name"
            name="name"
            type="text"
            class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            :value="old('name', $user->name)"
            required
            autofocus
        />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    {{-- Email --}}
    <div>
        <x-input-label for="email" :value="__('Email')" class="mb-1 font-semibold text-gray-700" />
        <x-text-input
            id="email"
            name="email"
            type="email"
            class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            :value="old('email', $user->email)"
            required
        />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    {{-- CAMPO DE ROL AÑADIDO --}}
    <div>
        <x-input-label for="role" :value="__('Rol del Usuario')" class="mb-1 font-semibold text-gray-700" />
        <select
            id="role"
            name="role"
            class="w-full p-3 text-base transition duration-150 ease-in-out border-gray-300 rounded-lg shadow-sm appearance-none focus:border-indigo-500 focus:ring-indigo-500"
        >
            {{-- La lógica por defecto selecciona 'usuario' si no se ha guardado un rol previamente --}}
            @php
                $currentRole = old('role', $user->role ?? 'usuario');
                $roles = ['usuario', 'trabajador', 'admin'];
            @endphp

            @foreach ($roles as $role)
                <option value="{{ $role }}" @if($currentRole === $role) selected @endif>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('role')" />

        {{-- COMENTARIO: Agregar aquí lógica de autorización si solo ciertos usuarios pueden cambiar el rol --}}
        {{-- COMENTARIO: La lógica de guardado debe ser implementada en el controlador para persistir el campo 'role' --}}

    </div>

    {{-- GRUPO DE 2 COLUMNAS: Contraseñas --}}
    <div class="grid grid-cols-1 gap-6 pt-2 border-t border-gray-100 md:grid-cols-2">

        {{-- Contraseña --}}
        <div>
            <x-input-label for="password" :value="__('Contraseña')" class="mb-1 font-semibold text-gray-700" />
            <x-text-input
                id="password"
                name="password"
                type="password"
                class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                autocomplete="new-password"
            />
            <x-input-error class="mt-2" :messages="$errors->get('password')" />
            @if($user->exists)
                {{-- Nota para Edición --}}
                <p class="mt-1 text-sm text-gray-500">{{ __('Dejar vacío para mantener la contraseña actual.') }}</p>
            @endif
        </div>

        {{-- Confirmar Contraseña --}}
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="mb-1 font-semibold text-gray-700" />
            <x-text-input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                autocomplete="new-password"
            />
            <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
        </div>

    </div>
</div>
