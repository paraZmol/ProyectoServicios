<x-guest-layout>

    <style>
        .login-button {
            background-color: #4f46e5;
            border: 1px solid #4f46e5;
            color: white;
            transition: background-color 0.15s ease-in-out;
            width: 100%;
            justify-content: center;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .login-button:hover {
            background-color: #4338ca;
        }

        .form-input-control {
            border-color: #d1d5db;
            transition: border-color 0.15s ease-in-out;
        }

        .form-input-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 1px #6366f1;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .forgot-password-link {
            color: #4f46e5;
            text-decoration: underline;
            transition: color 0.15s ease-in-out;
        }

        .forgot-password-link:hover {
            color: #3730a3;
        }
    </style>

    <x-slot name="logo">
        <a href="/">
            <img
                src="{{ $logoUrl }}" alt="{{ $companyName }}"
                alt="Logo de la Empresa"
                class="w-20 h-auto"
                style="max-width: 80px; margin: 0 auto; margin-bottom: 20px;"
            />
        </a>
    </x-slot>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h1 class="card-title">{{ __('Iniciar Sesión') }}</h1>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                          class="block w-full mt-1 form-input-control"
                          type="email" name="email"
                          :value="old('email')"
                          required autofocus autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password"
                          class="block w-full mt-1 form-input-control"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">

            {{-- recirdad funcion --}}
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500" name="remember">
                <span class="text-sm text-gray-600 ms-2">{{ __('Recordarme') }}</span>
            </label>

            {{-- contraseña olvidada --}}
            @if (Route::has('password.request'))
                <a class="text-sm rounded-md forgot-password-link" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu Contraseña?') }}
                </a>
            @endif
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ms-3 login-button">
                {{ __('ACCEDER') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
