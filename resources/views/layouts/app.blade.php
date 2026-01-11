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
                <header class="border-b shadow-md bg-[#272c5e]">
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

                                <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="py-4 font-bold text-yellow-300 celeste-nav-link hover:text-yellow-100">
                                    {{ __('Reportes Históricos') }}
                                </x-nav-link>

                                <x-nav-link :href="route('settings.edit')" :active="request()->routeIs('settings.*')" class="py-4 text-white celeste-nav-link">
                                    {{ __('Configuración') }}
                                </x-nav-link>

                            @endif

                            {{-- papelera de reciclaje cliente --}}
                            @php
                                // Verifica si alguna de las rutas de papelera está activa para resaltar el botón principal
                                $isAnyDeletedRouteActive = request()->routeIs('services.deleted') ||
                                                           request()->routeIs('clients.deleted') ||
                                                           request()->routeIs('invoices.deleted') ||
                                                           request()->routeIs('users.deleted');
                            @endphp

                            @php
                                // Verifica si alguna de las rutas de papelera está activa para resaltar el botón principal
                                $isAnyDeletedRouteActive = request()->routeIs('services.deleted') ||
                                                           request()->routeIs('clients.deleted') ||
                                                           request()->routeIs('invoices.deleted') ||
                                                           request()->routeIs('users.deleted');
                            @endphp

                            @if (Auth::check() && Auth::user()->role === 'admin')
                                <div class="relative flex items-center h-full" x-data="{ open: false }" @click.away="open = false">

                                    {{-- Botón Principal del Desplegable --}}
                                    {{-- Se eliminó 'celeste-nav-link' para controlar el tamaño y se usa 'border-b-4 border-transparent' para reservar espacio. --}}
                                    <button @click="open = ! open"
                                        class="inline-flex items-center text-sm leading-5 font-bold transition duration-150 ease-in-out py-4 px-3 text-white border-b-4 border-transparent
                                        {{ $isAnyDeletedRouteActive ? '!border-white !font-bold' : '' }}
                                        hover:bg-white hover:text-sky-700 hover:rounded-t-md"
                                        :class="{ '!border-white !font-bold': open || '{{ $isAnyDeletedRouteActive }}' }"
                                    >
                                        <i class="mr-1 fas fa-trash-restore"></i>
                                        {{ __('Papeleras') }}
                                        {{-- Icono de flecha --}}
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    {{-- Contenido del Desplegable --}}
                                    <div x-show="open"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 z-50 w-48 mt-2 origin-top-right rounded-md shadow-lg top-full"
                                        style="display: none;"
                                    >
                                        <div class="py-1 bg-white rounded-md ring-1 ring-black ring-opacity-5">
                                            {{-- Papelera Servicios --}}
                                            <a href="{{ route('services.deleted') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-100">
                                                <i class="mr-1 fas fa-trash-restore"></i>
                                                {{ __('Papelera Servicios') }}
                                            </a>
                                            {{-- Papelera Clientes --}}
                                            <a href="{{ route('clients.deleted') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-100">
                                                <i class="mr-1 fas fa-trash-restore"></i>
                                                {{ __('Papelera Clientes') }}
                                            </a>
                                            {{-- Papelera Boletas --}}
                                            <a href="{{ route('invoices.deleted') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-100">
                                                <i class="mr-1 fas fa-trash-restore"></i>
                                                {{ __('Papelera Boletas') }}
                                            </a>
                                            {{-- Papelera Usuarios --}}
                                            <a href="{{ route('users.deleted') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-100">
                                                <i class="mr-1 fas fa-trash-restore"></i>
                                                {{ __('Papelera Usuarios') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
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
