{{-- resources/views/invoices/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Nueva Factura') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="invoiceForm()">

                    {{-- resources/views/invoices/create.blade.php (DEBUG BLOCK) --}}
                    {{--  <div x-cloak x-show="invoiceData.items.length > 0" class="p-4 mb-4 text-sm text-gray-700 border border-blue-400 rounded-lg bg-blue-50/50">
                        <h4 class="pb-1 mb-2 font-bold border-b">DEBUG: Datos de la Boleta (Pre-Envío)</h4>
                        <pre x-text="JSON.stringify(invoiceData, null, 2)"></pre>
                        {{-- Muestra los items que Alpine ha calculado. --}}
                    {{--</div>
                    {{-- Fin del Bloque DEBUG --}}

                    {{-- mensaje de error --}}
                    @if (session('error'))
                        <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('invoices.store') }}" @submit.prevent="submitForm">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 pb-6 mb-6 border-b md:grid-cols-3">
                            {{-- seleccion del cliente --}}
                            {{--<div class="col-span-1">
                                <x-input-label for="client_id" :value="__('Cliente')" />
                                <select id="client_id" name="client_id" x-model.number="invoiceData.client_id"
                                    @change="updateClientInfo($el.options[$el.selectedIndex].text)"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">-- Seleccione un Cliente --</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" data-phone="{{ $client->telefono }}" data-email="{{ $client->email }}" data-address="{{ $client->direccion }}">
                                            {{ $client->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
                            </div>--}}

                                {{-- buscar y seleccionar con ajax --}}
                                <div class="relative col-span-2"
                                    x-data="{
                                        search: '',
                                        open: false,
                                        items: [],
                                        isLoading: false,

                                        // para buscar
                                        async fetchClients() {
                                            if (this.search.length < 2) {
                                                this.items = [];
                                                return;
                                            }
                                            this.isLoading = true;
                                            this.open = true;

                                            try {
                                                // llamada a la ruta de ajax
                                                let response = await fetch(`{{ route('clients.ajax.search') }}?q=${encodeURIComponent(this.search)}`);
                                                this.items = await response.json();
                                            } catch (e) {
                                                this.items = [];
                                            } finally {
                                                this.isLoading = false;
                                            }
                                        },

                                        // ala seleccionar un cliente
                                        selectClientFromDropdown(client) {
                                            // asignar id ocultao
                                            this.invoiceData.client_id = client.id;
                                            // mostrar en la busqueda
                                            this.search = client.nombre;
                                            // ocultar el deplegable
                                            this.open = false;
                                            // actualizar la infomracion latearl
                                            this.updateClientInfoFromObject(client);
                                        },

                                        // limpiar la busqueda
                                        resetSearch() {
                                            this.search = '';
                                            this.invoiceData.client_id = '';
                                            this.updateClientInfoFromObject(null);
                                            this.items = [];
                                        }
                                    }"
                                    @click.outside="open = false"
                                >
                                    <x-input-label for="client_search" :value="__('Cliente (Buscar DNI/RUC/Nombre)')" />

                                    <div class="relative mt-1">
                                        <x-text-input type="text" id="client_search" x-model="search"
                                            @input.debounce.500ms="fetchClients()"
                                            @focus="open = true"
                                            placeholder="Escriba nombre o DNI..." class="w-full pl-10" autocomplete="off"
                                        />

                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <i class="text-gray-400 fa" :class="isLoading ? 'fa-spinner fa-spin' : 'fa-search'"></i>
                                        </div>

                                        {{-- boton para limpiar --}}
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                                            x-show="search.length > 0 && !isLoading"
                                            @click="resetSearch()">
                                            <i class="text-gray-400 hover:text-red-500 fa fa-times"></i>
                                        </div>
                                    </div>

                                    {{-- info del imput par aguardar --}}
                                    <input type="hidden" name="client_id" x-model="invoiceData.client_id" required>

                                    {{-- lista deplegabnle de los resultados --}}
                                    <div x-show="open && items.length > 0 && search.length > 1"
                                        class="absolute z-50 w-full mt-1 overflow-y-auto bg-white border border-gray-200 rounded-md shadow-lg max-h-60"
                                        style="display: none;">
                                        <ul>
                                            <template x-for="item in items" :key="item.id">
                                                <li @click="selectClientFromDropdown(item)"
                                                    class="px-4 py-2 text-sm border-b cursor-pointer hover:bg-indigo-50 hover:text-indigo-800 border-gray-50 last:border-0">
                                                    <div class="font-bold text-gray-800" x-text="item.nombre"></div>
                                                    <div class="text-xs text-gray-500">
                                                        {{-- mostrar dni o ruc --}}
                                                            <span class="font-bold text-blue-600" x-text="item.tipo_documento + ':'"></span>
                                                            <span x-text="item.dni"></span> |
                                                        <span x-text="item.email"></span>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>

                                    <div x-show="open && items.length === 0 && search.length > 2 && !isLoading"
                                        class="absolute z-50 w-full p-2 mt-1 text-sm text-center text-red-500 bg-white border rounded-md shadow-lg">
                                        No se encontraron clientes.
                                    </div>

                                    <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
                                </div>

                            {{-- info del cliente --}}
                            <div class="col-span-1 space-y-2 text-sm pt-7">
                                <p><strong>Teléfono:</strong> <span x-text="clientInfo.phone"></span></p>
                                <p><strong>Email:</strong> <span x-text="clientInfo.email"></span></p>
                                <p><strong>Dirección:</strong> <span x-text="clientInfo.address"></span></p>
                            </div>
                        </div>

                        {{-- detalles de la factura --}}
                        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-4">
                            <div>
                                <x-input-label for="fecha" :value="__('Fecha')" />
                                <x-text-input id="fecha" name="fecha" type="date" class="block w-full mt-1" :value="old('fecha', date('Y-m-d'))" required x-model="invoiceData.fecha" />
                            </div>

                            <div>
                                <x-input-label for="estado" :value="__('Estado')" />
                                <select id="estado" name="estado" x-model="invoiceData.estado" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="Pagada">Pagada</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Anulada">Anulada</option>
                                </select>
                            </div>

                            {{-- PENDEINTE --}}
                            <div x-show="invoiceData.estado === 'Pendiente'" x-transition style="display: none;">
                                <x-input-label for="monto_pagado" :value="__('Monto a Cuenta (Adelanto)')" />
                                <x-text-input id="monto_pagado" name="monto_pagado" type="number" step="0.01"
                                    class="block w-full mt-1 border-blue-300 focus:border-[#2C326E] focus:ring-[#2C326E]"
                                    placeholder="0.00"
                                    x-model="invoiceData.monto_pagado" />
                            </div>

                            <div>
                                <x-input-label for="metodo_pago" :value="__('Método de Pago')" />
                                <select id="metodo_pago" name="metodo_pago" x-model="invoiceData.metodo_pago" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Digital">Billetera Digital</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label :value="__('Vendedor')" />
                                <x-text-input type="text" class="block w-full mt-1 bg-gray-100" value="{{ Auth::user()->name }}" disabled />
                            </div>
                        </div>
                        {{-- fin de detalles de factura --}}

                        {{-- tabla de items --}}
                        <h3 class="pt-4 mb-3 text-lg font-semibold border-t">{{ __('Servicios Incluidos') }}</h3>
                        <div class="mb-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Código | Servicio</th>
                                        <th class="px-3 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Cantidad</th>
                                        <th class="px-3 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Precio Unitario</th>
                                        <th class="px-3 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Total Línea</th>
                                        <th class="px-3 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                {{-- error de index --}}
                                <tbody class="bg-white divide-y divide-gray-200" x-ref="itemsTable">
                                    <template x-for="(item, index) in invoiceData.items" :key="index">
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-900 whitespace-nowrap">

                                                {{-- campos ocultos --}}
                                                <input type="hidden" x-bind:name="`items[${index}][service_id]`" x-model="item.service_id">
                                                <input type="hidden" x-bind:name="`items[${index}][nombre_servicio]`" x-model="item.nombre_servicio">

                                                {{-- total de linea --}}
                                                <input type="hidden" x-bind:name="`items[${index}][total_linea]`" x-model="item.total_linea">

                                                <p class="font-semibold" x-text="item.codigo + ' | ' + item.nombre_servicio"></p>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right whitespace-nowrap">
                                                {{-- cantidad --}}
                                                <x-text-input type="number" x-bind:name="`items[${index}][cantidad]`" x-model.number="item.cantidad" @input="calculateTotals" class="w-20 text-right" min="1" required />
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right whitespace-nowrap">
                                                {{-- precio --}}
                                                <x-text-input type="number" step="0.01" x-bind:name="`items[${index}][precio_unitario_final]`" x-model.number="item.precio_unitario_final" @input="calculateTotals" class="text-right w-28" min="0.01" required />
                                            </td>
                                            <td class="px-3 py-2 text-sm font-bold text-right whitespace-nowrap" x-text="formatCurrency(item.total_linea)"></td>
                                            <td class="px-3 py-2 text-sm font-medium text-right whitespace-nowrap">
                                                <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-900">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                {{-- fin del error de index --}}
                            </table>
                            <p class="mt-3 text-gray-600" x-show="invoiceData.items.length === 0">No se han añadido servicios a la factura.</p>
                        </div>

                        {{-- btn de añadir servicio --}}
                        <div class="mb-8">
                            <button type="button" @click="$refs.serviceSelectModal.showModal()" class="inline-flex items-center px-4 py-2 font-bold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
                                <i class="mr-2 fa fa-plus-circle"></i> {{ __('Añadir Servicio') }}
                            </button>
                        </div>

                        {{-- calculois finales --}}
                        <div class="flex justify-end">
                            <div class="w-full space-y-3 md:w-1/3">
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span class="font-bold" x-text="formatCurrency(invoiceData.subtotal)"></span>
                                    <input type="hidden" name="subtotal" x-model="invoiceData.subtotal">
                                </div>
                                <div class="flex justify-between pt-2 border-t">
                                    <span>Impuesto ({{ $iva_rate * 100 }}%):</span>
                                    <span class="font-bold" x-text="formatCurrency(invoiceData.impuesto)"></span>
                                    <input type="hidden" name="impuesto" x-model="invoiceData.impuesto">
                                </div>
                                <div class="flex justify-between pt-2 text-lg font-extrabold border-t">
                                    <span>TOTAL:</span>
                                    <span x-text="formatCurrency(invoiceData.total)"></span>
                                    <input type="hidden" name="total" x-model="invoiceData.total">
                                </div>
                            </div>
                        </div>

                        {{-- botones finales --}}
                        {{--<div class="flex justify-end mt-8 space-x-4">
                            <x-primary-button
                                type="submit"
                                x-bind:disabled="invoiceData.items.length === 0"
                                @click="invoiceData.estado = 'Pagada'"
                            >
                                {{ __('Guardar y Pagar') }}
                            </x-primary-button>
                            <x-primary-button
                                type="submit"
                                x-bind:disabled="invoiceData.items.length === 0"
                                class="bg-orange-500 hover:bg-orange-600"
                                @click="invoiceData.estado = 'Pendiente'"
                            >
                                {{ __('Guardar como Pendiente') }}
                            </x-primary-button>
                        </div>--}}
                        <div class="flex justify-end mt-8">
                            <x-primary-button
                                type="submit"
                                x-bind:disabled="invoiceData.items.length === 0"
                                class="bg-[#253891] hover:bg-[#2C326E]"
                            >
                                {{ __('Guardar Boleta') }}
                            </x-primary-button>
                        </div>
                    </form>

                    {{-- modal del seleccion de servicio --}}
                    <dialog x-ref="serviceSelectModal" class="p-6 rounded-lg shadow-2xl backdrop:bg-black/50">
                        <h3 class="mb-4 text-xl font-bold">{{ __('Seleccionar Servicio') }}</h3>
                        <div class="mb-4">
                            <x-text-input x-model="serviceSearch" placeholder="Buscar por código o nombre..." class="w-full" />
                        </div>
                        <div class="mb-4 overflow-y-auto max-h-64">
                            <ul class="divide-y divide-gray-200">
                                <template x-for="service in filteredServices" :key="service.id">
                                    <li class="flex items-center justify-between p-2 cursor-pointer hover:bg-gray-100"
                                        @click="addItem(service); $refs.serviceSelectModal.close()">
                                        <div>
                                            <p class="font-semibold" x-text="service.codigo + ' | ' + service.nombre_servicio"></p>
                                            <p class="text-sm text-gray-500" x-text="formatCurrency(service.precio)"></p>
                                        </div>
                                        <i class="text-green-500 fa fa-plus"></i>
                                    </li>
                                </template>
                                <li x-show="filteredServices.length === 0" class="p-2 text-center text-gray-500">No se encontraron servicios.</li>
                            </ul>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="$refs.serviceSelectModal.close()" class="px-4 py-2 font-bold text-gray-800 bg-gray-300 rounded hover:bg-gray-400">
                                {{ __('Cerrar') }}
                            </button>
                        </div>
                    </dialog>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- LÓGICA DE ALPINE.JS --}}
<script>
    function invoiceForm() {
        // datos
        const allServices = @json($services);
        const IVA_RATE = @json($iva_rate);

        return {
            IVA_RATE: IVA_RATE,
            serviceSearch: '',
            clientInfo: { name: '', phone: '', email: '', address: '' },

            invoiceData: {
                // header de la factura
                client_id: '',
                fecha: '{{ date('Y-m-d') }}',
                estado: 'Pagada',
                metodo_pago: 'Efectivo',

                // para el estado de Pendiente
                monto_pagado: 0,

                // items y canculos
                items: @json(old('items', [])),
                subtotal: 0,
                impuesto: 0,
                total: 0,
            },

            init() {
                this.calculateTotals();
            },

            // foltrado de servicios para el modal
            get filteredServices() {
                if (!this.serviceSearch) {
                    return allServices;
                }
                const searchLower = this.serviceSearch.toLowerCase();
                return allServices.filter(service =>
                    service.nombre_servicio.toLowerCase().includes(searchLower) ||
                    service.codigo.toLowerCase().includes(searchLower)
                );
            },


            // añadir item del modal
            addItem(service) {

                let rate = (typeof this.IVA_RATE !== 'undefined' && this.IVA_RATE !== null) ? this.IVA_RATE : 0.18;

                const existingItemIndex = this.invoiceData.items.findIndex(item => item.service_id === service.id);

                if (existingItemIndex > -1) {
                    this.invoiceData.items[existingItemIndex].cantidad++;
                } else {
                    let precioNeto = parseFloat((service.precio * (1 - rate)).toFixed(2));

                    this.invoiceData.items.push({
                        service_id: service.id,
                        codigo: service.codigo,
                        nombre_servicio: service.nombre_servicio,
                        cantidad: 1,
                        precio_unitario_final: precioNeto,
                        total_linea: precioNeto,
                    });
                }

                this.serviceSearch = '';
                this.calculateTotals();
            },

            /*addItem(service) {
                const existingItemIndex = this.invoiceData.items.findIndex(item => item.service_id === service.id);

                if (existingItemIndex > -1) {
                    // si es que ya existe incrementemos el modal
                    this.invoiceData.items[existingItemIndex].cantidad++;
                } else {
                    // de no existir creamos unoi nuevo
                    this.invoiceData.items.push({
                        service_id: service.id,
                        codigo: service.codigo,
                        nombre_servicio: service.nombre_servicio,
                        cantidad: 1,
                        precio_unitario_final: parseFloat(service.precio), // precio modiicable
                        total_linea: parseFloat(service.precio),
                    });
                }

                this.serviceSearch = ''; // limpia de busqueda
                this.calculateTotals();
            },*/

            // eliminar item
            removeItem(index) {
                this.invoiceData.items.splice(index, 1);
                this.calculateTotals();
            },

            // calcula totales
            calculateTotals() {
                let rate = (typeof this.IVA_RATE !== 'undefined' && this.IVA_RATE !== null) ? this.IVA_RATE : 0.18;

                let totalAcumuladoNeto = 0;

                this.invoiceData.items.forEach(item => {
                    const cantidad = parseFloat(item.cantidad) || 0;
                    const precio = parseFloat(item.precio_unitario_final) || 0;

                    const totalLinea = cantidad * precio;

                    item.total_linea = totalLinea;
                    totalAcumuladoNeto += totalLinea;
                });

                this.invoiceData.subtotal = parseFloat(totalAcumuladoNeto.toFixed(2));

                this.invoiceData.total = parseFloat((this.invoiceData.subtotal / (1 - rate)).toFixed(2));

                this.invoiceData.impuesto = parseFloat((this.invoiceData.total - this.invoiceData.subtotal).toFixed(2));

                console.log('Subtotal (Tabla):', this.invoiceData.subtotal, 'Total Calc:', this.invoiceData.total);
            },

            /*calculateTotals() {
                let subtotal = 0;
                this.invoiceData.items.forEach(item => {
                    // cantidad y precio en numeros - verificacion
                    const cantidad = parseFloat(item.cantidad) || 0;
                    const precio = parseFloat(item.precio_unitario_final) || 0;

                    const totalLinea = cantidad * precio;
                    item.total_linea = totalLinea;
                    subtotal += totalLinea;
                });

                this.invoiceData.subtotal = parseFloat(subtotal.toFixed(2));
                this.invoiceData.impuesto = parseFloat((subtotal * this.IVA_RATE).toFixed(2));
                this.invoiceData.total = parseFloat((this.invoiceData.subtotal + this.invoiceData.impuesto).toFixed(2));
            },*/

updateClientInfoFromObject(clientObj) {
    if (clientObj) {
        this.clientInfo.name = clientObj.nombre || '';
        this.clientInfo.phone = clientObj.telefono || 'N/A';
        this.clientInfo.email = clientObj.email || 'N/A';
        this.clientInfo.address = clientObj.direccion || 'N/A';
    } else {
        // Limpiar información si no hay cliente seleccionado
        this.clientInfo = { name: '', phone: '', email: '', address: '' };
    }
},

            // formato de moneda
            formatCurrency(value) {
                const setting = @json($setting); // obtener el seting
                const symbol = setting ? setting.simbolo_moneda : '$';
                return symbol + ' ' + value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },

            // envio de formulario
            submitForm(event) {
                this.calculateTotals();
                //alert('DEBUG: Enviando datos validados al servidor.');
                event.currentTarget.submit();
            }
        }
    }
</script>
