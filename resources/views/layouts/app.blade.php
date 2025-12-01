<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="icon" href="{{ $faviconUrl }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .celeste-nav-link {
                transition: all 0.15s ease-in-out;
            }
            .celeste-nav-link.active {
                color: white !important;
                border-bottom: 3px solid white !important;
                font-weight: 700;
            }
            .celeste-nav-link:hover:not(.active) {
                background-color: white;
                color: #0369A1;
                border-bottom: 3px solid transparent;
                border-radius: 4px;
                padding-top: 10px;
                padding-bottom: 10px;
            }
        </style>

    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="border-b shadow-md bg-sky-700 border-sky-800">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">

                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="py-4 text-white celeste-nav-link">
                                {{ __('Inicio') }}
                            </x-nav-link>

                            <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')" class="py-4 text-white celeste-nav-link">
                                {{ __('Servicios') }}
                            </x-nav-link>

                            @if (Auth::user()->role !=='usuario')
                                <x-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')" class="py-4 text-white celeste-nav-link">
                                    {{ __('Clientes') }}
                                </x-nav-link>

                                <x-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')" class="py-4 text-white celeste-nav-link">
                                    {{ __('Boletas') }}
                                </x-nav-link>
                            @endif

                            @if (Auth::check() && Auth::user()->role ==='admin')

                                <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="py-4 text-white celeste-nav-link">
                                    {{ __('Usuarios') }}
                                </x-nav-link>

                            @endif

                            @if (Auth::user()->role !=='usuario')

                                <x-nav-link :href="route('settings.edit')" :active="request()->routeIs('settings.*')" class="py-4 text-white celeste-nav-link">
                                    {{ __('Configuración') }}
                                </x-nav-link>
                            @endif

                            {{-- papelera de reciclaje cliente --}}
                            @if (Auth::check() && Auth::user()->role === 'admin')
                                <x-nav-link :href="route('clients.deleted')" :active="request()->routeIs('clients.deleted')" class="py-4 text-white celeste-nav-link">
                                    <i class="mr-1 fas fa-trash-restore"></i>
                                    {{ __('Papelera') }}
                                </x-nav-link>

                                <x-nav-link :href="route('services.deleted')" :active="request()->routeIs('services.deleted')" class="py-4 text-white celeste-nav-link">
                                    <i class="mr-1 fas fa-trash-restore"></i>
                                    {{ __('Papelera Servicios') }}
                                </x-nav-link>
                            @endif
                        </div>
                    </div>
                </header>
            @endisset

            <main class="py-12">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
