<style>
    .celeste-nav-link {
        transition: all 0.15s ease-in-out;
    }
    .celeste-nav-link.active {
        color: white !important;
        border-bottom: 3px solid white !important;
        font-weight: 700;
    }
    .celeste-nav-link:hover:not(.active-link) {
        background-color: white;
        color: #0369A1;
        border-bottom: 3px solid transparent;
        border-radius: 4px;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
</style>

<nav x-data="{ open: false }" class="border-b shadow-xl bg-[#272c5e]">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <img src="{{ $logoUrl }}" alt="{{ $companyName }}" class="block w-auto bg-white rounded-full h-14"/>
                        <span class="hidden text-xl font-extrabold tracking-wider text-gray-100 sm:inline">{{ $companyName }}</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Aquí irían los enlaces de escritorio si los agregas después -->
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center px-3 py-2 text-sm font-semibold leading-4 text-white transition duration-200 ease-in-out border rounded-lg shadow-sm bg-[#1f2245] border-white/30 hover:bg-white hover:text-[#1f2245] focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-sky-700">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                            <x-dropdown-link :href="route('profile.edit')" class="text-gray-700 transition duration-150 ease-in-out hover:underline">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();"
                                        class="text-red-600 transition duration-150 ease-in-out hover:bg-red-50 hover:underline">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-white transition duration-150 ease-in-out rounded-md hover:text-white hover:bg-sky-600 focus:outline-none focus:bg-sky-600 focus:text-white">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- responsive menu --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-sky-900 sm:hidden bg-sky-800">

        {{-- estilos para el menu --}}
        @php
            // base
            $base = "border-l-4 transition duration-150 ease-in-out block w-full pl-3 pr-4 py-2 text-left text-base font-medium";

            // principal
            $activo   = "border-sky-500 text-black bg-white font-bold";
            $inactivo = "border-transparent text-white hover:bg-sky-700 hover:border-gray-300 hover:text-white";

            // papeleres
            $papeleraBase     = "pl-8 " . $base;
            $papeleraActivo   = "border-sky-500 text-black bg-white font-bold";
            $papeleraInactivo = "border-transparent text-sky-100 hover:bg-sky-700 hover:text-white hover:border-white";
        @endphp

        <div class="pt-2 pb-3 space-y-1">
            <!-- nombre de empresa -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="{{ $base }} {{ request()->routeIs('dashboard') ? $activo : $inactivo }}">
                {{ $companyName }}
            </x-responsive-nav-link>
        </div>

        {{-- inicio menu agregado --}}
        <div class="pt-2 pb-3 space-y-1">

            <!-- menu de inicio -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="{{ $base }} {{ request()->routeIs('dashboard') ? $activo : $inactivo }}">
                {{ __('Inicio') }}
            </x-responsive-nav-link>

            <!-- menu de servicios -->
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')"
                class="{{ $base }} {{ request()->routeIs('services.*') ? $activo : $inactivo }}">
                {{ __('Servicios') }}
            </x-responsive-nav-link>

            <!-- menu de clientes y boletas -->
            @if (Auth::user()->role !== 'usuario')
                <x-responsive-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')"
                    class="{{ $base }} {{ request()->routeIs('clients.*') ? $activo : $inactivo }}">
                    {{ __('Clientes') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')"
                    class="{{ $base }} {{ request()->routeIs('invoices.*') ? $activo : $inactivo }}">
                    {{ __('Boletas') }}
                </x-responsive-nav-link>
            @endif

            <!-- visualizacion del admin -->
            @if (Auth::check() && Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')"
                    class="{{ $base }} {{ request()->routeIs('users.*') ? $activo : $inactivo }}">
                    {{ __('Usuarios') }}
                </x-responsive-nav-link>

                {{-- reportes historiocs --}}
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')"
                    class="{{ $base }} {{ request()->routeIs('reports.*') ? $activo : $inactivo }}">
                    {{ __('Reportes Históricos') }}
                </x-responsive-nav-link>
            @endif

            <!-- configuracion -->
            @if (Auth::user()->role !== 'usuario')
                <x-responsive-nav-link :href="route('settings.edit')" :active="request()->routeIs('settings.*')"
                    class="{{ $base }} {{ request()->routeIs('settings.*') ? $activo : $inactivo }}">
                    {{ __('Configuración') }}
                </x-responsive-nav-link>
            @endif

            <!-- papeleras -->
            @if (Auth::check() && Auth::user()->role === 'admin')
                <div class="pt-2 mt-4 border-t border-sky-600/50">
                    <div class="px-4 py-2 text-xs font-bold tracking-widest uppercase text-sky-300">
                        <i class="mr-1 fas fa-trash-restore"></i> {{ __('Papeleras') }}
                    </div>

                    <x-responsive-nav-link :href="route('services.deleted')" :active="request()->routeIs('services.deleted')"
                        class="{{ $papeleraBase }} {{ request()->routeIs('services.deleted') ? $papeleraActivo : $papeleraInactivo }}">
                        {{ __('Servicios') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('clients.deleted')" :active="request()->routeIs('clients.deleted')"
                        class="{{ $papeleraBase }} {{ request()->routeIs('clients.deleted') ? $papeleraActivo : $papeleraInactivo }}">
                        {{ __('Clientes') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('invoices.deleted')" :active="request()->routeIs('invoices.deleted')"
                        class="{{ $papeleraBase }} {{ request()->routeIs('invoices.deleted') ? $papeleraActivo : $papeleraInactivo }}">
                        {{ __('Boletas') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('users.deleted')" :active="request()->routeIs('users.deleted')"
                        class="{{ $papeleraBase }} {{ request()->routeIs('users.deleted') ? $papeleraActivo : $papeleraInactivo }}">
                        {{ __('Usuarios') }}
                    </x-responsive-nav-link>
                </div>
            @endif
        </div>

        <!-- opciones de perfil -->
        <div class="pt-4 pb-1 border-t shadow-inner border-sky-900">
            <div class="px-4">
                <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-sky-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:bg-sky-700 hover:text-white">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- autenticacion -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-red-300 transition-colors hover:bg-red-800 hover:text-white">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        {{-- fin menu agregado --}}
    </div>
</nav>
