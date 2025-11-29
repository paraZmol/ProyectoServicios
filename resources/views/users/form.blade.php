@props(['user' => new App\Models\User()])

<div class="space-y-6">
    {{-- Nombre --}}
    <div>
        <x-input-label for="name" :value="__('Nombre')" />
        <x-text-input id="name" name="name" type="text" class="block w-full mt-1" :value="old('name', $user->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    {{-- Email --}}
    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $user->email)" required />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    {{-- Contraseña --}}
    <div>
        <x-input-label for="password" :value="__('Contraseña')" />
        <x-text-input id="password" name="password" type="password" class="block w-full mt-1" autocomplete="new-password" />
        <x-input-error class="mt-2" :messages="$errors->get('password')" />
        @if($user->exists)
            <p class="mt-1 text-sm text-gray-500">{{ __('Dejar vacío para mantener la contraseña actual.') }}</p>
        @endif
    </div>

    {{-- Confirmar Contraseña (Necesario por la regla 'confirmed') --}}
    <div>
        <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block w-full mt-1" autocomplete="new-password" />
        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
    </div>
</div>
