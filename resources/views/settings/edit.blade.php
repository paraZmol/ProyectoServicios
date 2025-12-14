<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold leading-tight text-gray-800">
            {{ __('Configuración del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- mensaje de logro --}}
            @if (session('success'))
                <div class="p-4 mb-6 font-medium text-white bg-green-500 rounded-lg shadow-md" role="alert">
                    <p class="flex items-center"><i class="mr-2 fas fa-check-circle"></i> {{ session('success') }}</p>
                </div>
            @endif

            {{-- mesaje error --}}
            @if (session('error'))
                <div class="p-4 mb-6 font-medium text-white bg-red-500 rounded-lg shadow-md" role="alert">
                    <p class="flex items-center"><i class="mr-2 fas fa-times-circle"></i> {{ session('error') }}</p>
                </div>
            @endif

            <div class="p-8 overflow-hidden bg-white shadow-2xl sm:rounded-xl">

                {{-- subida de logo --}}
                <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-10 md:grid-cols-2">

                        {{-- columa isquierda --}}
                        <div class="space-y-6">
                            <h3 class="pb-3 mb-6 text-2xl font-bold text-[#2C326E] border-b border-indigo-200">
                                <i class="mr-2 fas fa-building"></i> {{ __('Información General y Fiscal') }}
                            </h3>

                            {{-- nombre empresa --}}
                            <div>
                                <x-input-label for="nombre_empresa" :value="__('Nombre de la empresa')" class="mb-1 font-semibold text-gray-700" />
                                <x-text-input
                                    id="nombre_empresa"
                                    name="nombre_empresa"
                                    type="text"
                                    class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :value="old('nombre_empresa', $setting->nombre_empresa)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('nombre_empresa')" />
                            </div>

                            {{-- RUC de la empresa --}}
                            <div>
                                <x-input-label for="ruc" :value="__('RUC de la empresa')" class="mb-1 font-semibold text-gray-700" />
                                <x-text-input
                                    id="ruc"
                                    name="ruc"
                                    type="text"
                                    class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :value="old('ruc', $setting->ruc ?? '')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('ruc')" />
                            </div>

                            {{-- columna derecha antes de los logos --}}
                            <div class="grid grid-cols-2 gap-4">
                                {{-- IVA --}}
                                <div>
                                    <x-input-label for="iva_porcentaje" :value="__('IVA (%)')" class="mb-1 font-semibold text-gray-700" />
                                    <x-text-input id="iva_porcentaje" name="iva_porcentaje" type="number" step="0.01" min="0" max="100"
                                        class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :value="old('iva_porcentaje', $setting->iva_porcentaje)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('iva_porcentaje')" />
                                </div>

                                {{-- moneda --}}
                                <div>
                                    <x-input-label for="simbolo_moneda" :value="__('Símbolo de moneda')" class="mb-1 font-semibold text-gray-700" />
                                    <x-text-input id="simbolo_moneda" name="simbolo_moneda" type="text"
                                        class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :value="old('simbolo_moneda', $setting->simbolo_moneda)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('simbolo_moneda')" />
                                </div>
                            </div>

                            <hr class="pt-4 border-gray-100">

                            {{-- seccion d eimagenes --}}
                            <h3 class="pb-3 mb-4 text-xl font-bold text-gray-700">{{ __('Imágenes del Sistema') }}</h3>

                            {{-- vista previa de logo --}}
                            <div class="pb-2">
                                <x-input-label :value="__('Logo de la empresa')" class="mb-1 font-semibold text-gray-700" />

                                {{-- contenedor de vista previa --}}
                                <div class="inline-block p-2 mt-2 mb-4 border border-gray-200 rounded-lg bg-gray-50">
                                    <img
                                        id="logo-preview"
                                        src="{{ $setting->logo_path ? Storage::url($setting->logo_path) : asset('img/logo_default.png') }}"
                                        alt="Logo de la Empresa"
                                        class="block w-40 h-auto rounded shadow-md"
                                    >
                                </div>

                                {{-- input de archivo --}}
                                <input
                                    type="file"
                                    id="logo_file"
                                    name="logo_file"
                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#1E3A8A] hover:file:bg-blue-100"
                                    onchange="previewLogo(event)"
                                />

                                {{-- requisito de logo --}}
                                <p class="mt-1 text-xs text-gray-500">
                                    Formatos: JPG, PNG, GIF, SVG. Máximo: 10MB
                                </p>

                                <x-input-error class="mt-2" :messages="$errors->get('logo_file')" />
                            </div>

                            {{-- icono vista previa --}}
                            <div class="pb-2">
                                <x-input-label :value="__('Icono de la empresa para la pestaña (Favicon)')" class="mb-1 font-semibold text-gray-700" />

                                {{-- contenedor --}}
                                <div class="inline-block p-2 mt-2 mb-4 border border-gray-200 rounded-lg bg-gray-50">
                                    <img
                                        id="favicon-preview"
                                        src="{{ $setting->favicon_path ? Storage::url($setting->favicon_path) : asset('img/icon_default.png') }}"
                                        alt="Icono de la pestaña"
                                        class="block w-20 h-auto rounded shadow-md"
                                    >
                                </div>

                                {{-- input --}}
                                <input
                                    type="file"
                                    id="favicon_file"
                                    name="favicon_file"
                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,image/x-icon"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#1E3A8A] hover:file:bg-blue-100"
                                    onchange="previewFavicon(event)"
                                />

                                {{-- requisitos --}}
                                <p class="mt-1 text-xs text-gray-500">
                                    Formatos: JPG, PNG, GIF, SVG, ICO. Máximo: 500KB
                                </p>

                                <x-input-error class="mt-2" :messages="$errors->get('favicon_file')" />
                            </div>
                        </div>

                        {{-- columna derecha --}}
                        <div class="space-y-6">
                            <h3 class="pb-3 mb-6 text-2xl font-bold text-green-700 border-b border-green-200">
                                <i class="mr-2 fas fa-map-marker-alt"></i> {{ __('Contacto y Ubicación') }}
                            </h3>

                            {{-- fono --}}
                            <div>
                                <x-input-label for="telefono" :value="__('Teléfono')" class="mb-1 font-semibold text-gray-700" />
                                <x-text-input id="telefono" name="telefono" type="text"
                                    class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :value="old('telefono', $setting->telefono)" />
                                <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
                            </div>

                            {{-- email --}}
                            <div>
                                <x-input-label for="correo_electronico" :value="__('Correo electrónico')" class="mb-1 font-semibold text-gray-700" />
                                <x-text-input id="correo_electronico" name="correo_electronico" type="email"
                                    class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :value="old('correo_electronico', $setting->correo_electronico)" />
                                <x-input-error class="mt-2" :messages="$errors->get('correo_electronico')" />
                            </div>

                            {{-- direccion --}}
                            <div>
                                <x-input-label for="direccion" :value="__('Dirección (Calle y Número)')" class="mb-1 font-semibold text-gray-700" />
                                <x-text-input id="direccion" name="direccion" type="text"
                                    class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :value="old('direccion', $setting->direccion)" />
                                <x-input-error class="mt-2" :messages="$errors->get('direccion')" />
                            </div>

                            <hr class="border-gray-100">

                            {{-- region y provincia --}}
                            <div class="grid grid-cols-2 gap-4">
                                {{-- reg --}}
                                <div>
                                    <x-input-label for="region" :value="__('Región/Departamento')" class="mb-1 font-semibold text-gray-700" />
                                    <x-text-input id="region" name="region" type="text"
                                        class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :value="old('region', $setting->region)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('region')" />
                                </div>

                                {{-- prov --}}
                                <div>
                                    <x-input-label for="provincia" :value="__('Provincia')" class="mb-1 font-semibold text-gray-700" />
                                    <x-text-input id="provincia" name="provincia" type="text"
                                        class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :value="old('provincia', $setting->provincia)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('provincia')" />
                                </div>
                            </div>

                            {{-- ciudad y codigi --}}
                            <div class="grid grid-cols-2 gap-4">
                                {{-- ciudad --}}
                                <div>
                                    <x-input-label for="ciudad" :value="__('Ciudad/Distrito')" class="mb-1 font-semibold text-gray-700" />
                                    <x-text-input id="ciudad" name="ciudad" type="text"
                                        class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :value="old('ciudad', $setting->ciudad)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('ciudad')" />
                                </div>

                                {{-- codigo postal --}}
                                <div>
                                    <x-input-label for="codigo_postal" :value="__('Código postal')" class="mb-1 font-semibold text-gray-700" />
                                    <x-text-input id="codigo_postal" name="codigo_postal" type="text"
                                        class="w-full p-3 text-base border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :value="old('codigo_postal', $setting->codigo_postal)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('codigo_postal')" />
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="flex justify-end pt-8 mt-8 border-t border-gray-100">
                        <x-primary-button type="submit" class="px-8 py-3 text-base font-bold transition duration-150 ease-in-out bg-[#253891] rounded-lg shadow-md hover:bg-[#2C326E]">
                            <i class="mr-2 fas fa-sync-alt"></i> {{ __('Actualizar datos') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- para vista previa de la subida de imagens --}}
    <script>
        function previewLogo(event) {
            const input = event.target;
            const preview = document.getElementById('logo-preview');

            // verificar que se sleccion un archivo
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // validar imagen
                if (!file.type.startsWith('image/')) {
                    alert('Por favor selecciona un archivo de imagen válido');
                    input.value = '';
                    return;
                }

                // validar tamaño
                const maxSize = 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('El archivo es demasiado grande. Máximo: 10MB');
                    input.value = ''; // Limpiar el input
                    return;
                }

                // lectura de archivo
                const reader = new FileReader();

                // actualizar la imagen
                reader.onload = function(e) {
                    preview.src = e.target.result;

                    // efecto fade
                    preview.style.opacity = '0.5';
                    setTimeout(() => {
                        preview.style.opacity = '1';
                    }, 100);
                };

                // leer url con base64
                reader.readAsDataURL(file);
            }
        }

        // para el icono
        function previewFavicon(event) {
            const input = event.target;
            const preview = document.getElementById('favicon-preview');

            // verificar archivo
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // validar iamgen
                if (!file.type.startsWith('image/')) {
                    alert('Por favor selecciona un archivo de imagen válido');
                    input.value = '';
                    return;
                }

                // tamaño
                const maxSize = 500 * 1024;
                if (file.size > maxSize) {
                    alert('El archivo es demasiado grande. Máximo: 500KB');
                    input.value = '';
                    return;
                }

                // crear la lectura de archivo
                const reader = new FileReader();

                // actulizar
                reader.onload = function(e) {
                    preview.src = e.target.result;

                    // efecto fade
                    preview.style.opacity = '0.5';
                    setTimeout(() => {
                        preview.style.opacity = '1';
                    }, 100);
                };

                // lecutra con base 64
                reader.readAsDataURL(file);
            }
        }

        // trancision de imagens
        document.addEventListener('DOMContentLoaded', function() {
            const logoPreview = document.getElementById('logo-preview');
            const faviconPreview = document.getElementById('favicon-preview');

            if (logoPreview) {
                logoPreview.style.transition = 'opacity 0.3s ease-in-out';
            }

            if (faviconPreview) {
                faviconPreview.style.transition = 'opacity 0.3s ease-in-out';
            }
        });
    </script>
</x-app-layout>
