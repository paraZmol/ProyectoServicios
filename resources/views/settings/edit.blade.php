<x-app-layout><x-slot name="header"><h2 class="text-xl font-semibold leading-tight text-gray-800">{{ __('Configuracion del Sistema') }}</h2></x-slot><div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

        @if (session('success'))
            <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

            {{-- el atributo 'enctype' es esencial para la subida de archivos (logo) --}}
            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

                    {{-- columna izquierda: logo y general --}}
                    <div class="space-y-6">
                        <h3 class="pb-2 mb-4 text-lg font-semibold border-b">{{ __('Informacion General') }}</h3>

                        {{-- seccion del logo --}}
                        <div>
                            <x-input-label :value="__('Logo de la empresa')" />
                            @if ($setting->logo_path)
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url($setting->logo_path) }}"
                                        alt="Logo de la Empresa"
                                        class="max-width: 150px; height: auto; display: block; margin-bottom: 10px;">
                                </div>
                            @endif

                            <input type="file" id="logo_file" name="logo_file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100"/>
                            <x-input-error class="mt-2" :messages="$errors->get('logo_file')" />
                        </div>

                        {{-- nombre de la empresa --}}
                        <div>
                            <x-input-label for="nombre_empresa" :value="__('Nombre de la empresa')" />
                            <x-text-input id="nombre_empresa" name="nombre_empresa" type="text" class="block w-full mt-1" :value="old('nombre_empresa', $setting->nombre_empresa)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nombre_empresa')" />
                        </div>

                        {{-- iva (%) --}}
                        <div>
                            <x-input-label for="iva_porcentaje" :value="__('IVA (%)')" />
                            <x-text-input id="iva_porcentaje" name="iva_porcentaje" type="number" step="0.01" min="0" max="100" class="block w-full mt-1" :value="old('iva_porcentaje', $setting->iva_porcentaje)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('iva_porcentaje')" />
                        </div>

                        {{-- simbolo de moneda --}}
                        <div>
                            <x-input-label for="simbolo_moneda" :value="__('Simbolo de moneda')" />
                            <x-text-input id="simbolo_moneda" name="simbolo_moneda" type="text" class="block w-full mt-1" :value="old('simbolo_moneda', $setting->simbolo_moneda)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('simbolo_moneda')" />
                        </div>
                    </div>

                    {{-- lado derecho, contanto y ubicacion --}}
                    <div class="space-y-6">
                        <h3 class="pb-2 mb-4 text-lg font-semibold border-b">{{ __('Contacto y Ubicacion') }}</h3>

                        {{-- telefono --}}
                        <div>
                            <x-input-label for="telefono" :value="__('Telefono')" />
                            <x-text-input id="telefono" name="telefono" type="text" class="block w-full mt-1" :value="old('telefono', $setting->telefono)" />
                            <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
                        </div>

                        {{-- correo electronico (nombre corregido) --}}
                        <div>
                            <x-input-label for="correo_electronico" :value="__('Correo electronico')" />
                            <x-text-input id="correo_electronico" name="correo_electronico" type="email" class="block w-full mt-1" :value="old('correo_electronico', $setting->correo_electronico)" />
                            <x-input-error class="mt-2" :messages="$errors->get('correo_electronico')" />
                        </div>

                        {{-- direccion --}}
                        <div>
                            <x-input-label for="direccion" :value="__('Direccion (Calle y Numero)')" />
                            <x-text-input id="direccion" name="direccion" type="text" class="block w-full mt-1" :value="old('direccion', $setting->direccion)" />
                            <x-input-error class="mt-2" :messages="$errors->get('direccion')" />
                        </div>

                        {{-- region --}}
                        <div>
                            <x-input-label for="region" :value="__('Region/Departamento')" />
                            <x-text-input id="region" name="region" type="text" class="block w-full mt-1" :value="old('region', $setting->region)" />
                            <x-input-error class="mt-2" :messages="$errors->get('region')" />
                        </div>

                        {{-- provincia --}}
                        <div>
                            <x-input-label for="provincia" :value="__('Provincia')" />
                            <x-text-input id="provincia" name="provincia" type="text" class="block w-full mt-1" :value="old('provincia', $setting->provincia)" />
                            <x-input-error class="mt-2" :messages="$errors->get('provincia')" />
                        </div>

                        {{-- ciudad --}}
                        <div>
                            <x-input-label for="ciudad" :value="__('Ciudad/Distrito')" />
                            <x-text-input id="ciudad" name="ciudad" type="text" class="block w-full mt-1" :value="old('ciudad', $setting->ciudad)" />
                            <x-input-error class="mt-2" :messages="$errors->get('ciudad')" />
                        </div>

                        {{-- codigo postal --}}
                        <div>
                            <x-input-label for="codigo_postal" :value="__('Codigo postal')" />
                            <x-text-input id="codigo_postal" name="codigo_postal" type="text" class="block w-full mt-1" :value="old('codigo_postal', $setting->codigo_postal)" />
                            <x-input-error class="mt-2" :messages="$errors->get('codigo_postal')" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 mt-6 border-t">
                    <x-primary-button type="submit">
                        {{ __('Actualizar datos') }}
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</div>
</x-app-layout>
