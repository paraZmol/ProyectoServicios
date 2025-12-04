<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Editar Boleta #') }}{{ $invoice->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="invoiceForm()">

                    @if (session('error'))
                        <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('invoices.update', $invoice->id) }}" @submit.prevent="submitForm">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 pb-6 mb-6 border-b md:grid-cols-3">
                            {{-- seleccion del cliente --}}
                            <div class="col-span-1">
                                <x-input-label for="client_id" :value="__('Cliente')" />
                                <select id="client_id" name="client_id" x-model.number="invoiceData.client_id"
                                    @change="updateClientInfo($el.options[$el.selectedIndex].text)"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">-- Seleccione un Cliente --</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            @selected(old('client_id', $invoice->client_id) == $client->id)
                                            data-phone="{{ $client->telefono }}" data-email="{{ $client->email }}" data-address="{{ $client->direccion }}">
                                            {{ $client->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
                            </div>

                            {{-- info del cliente seleccionado --}}
                            <div class="col-span-2 space-y-2 text-sm pt-7">
                                <p><strong>Teléfono:</strong> <span x-text="clientInfo.phone"></span></p>
                                <p><strong>Email:</strong> <span x-text="clientInfo.email"></span></p>
                                <p><strong>Dirección:</strong> <span x-text="clientInfo.address"></span></p>
                            </div>
                        </div>

                        {{-- detalle de la factura --}}
                        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-4">
                            <div>
                                <x-input-label for="fecha" :value="__('Fecha')" />
                                <x-text-input id="fecha" name="fecha" type="date" class="block w-full mt-1" :value="old('fecha', $invoice->fecha)" required x-model="invoiceData.fecha" />
                            </div>
                            <div>
                                <x-input-label for="estado" :value="__('Estado')" />
                                <select id="estado" name="estado" x-model="invoiceData.estado" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="Pagada" @selected(old('estado', $invoice->estado) == 'Pagada')>Pagada</option>
                                    <option value="Pendiente" @selected(old('estado', $invoice->estado) == 'Pendiente')>Pendiente</option>
                                    <option value="Anulada" @selected(old('estado', $invoice->estado) == 'Anulada')>Anulada</option>
                                </select>
                            </div>
                             <div>
                                <x-input-label for="metodo_pago" :value="__('Método de Pago')" />
                                <select id="metodo_pago" name="metodo_pago" x-model="invoiceData.metodo_pago" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="Efectivo" @selected(old('metodo_pago', $invoice->metodo_pago) == 'Efectivo')>Efectivo</option>
                                    <option value="Transferencia" @selected(old('metodo_pago', $invoice->metodo_pago) == 'Transferencia')>Transferencia</option>
                                    <option value="Digital" @selected(old('metodo_pago', $invoice->metodo_pago) == 'Digital')>Billetera Digital</option>
                                </select>
                            </div>
                             <div>
                                <x-input-label :value="__('Vendedor')" />
                                <x-text-input type="text" class="block w-full mt-1 bg-gray-100" value="{{ $invoice->user->name ?? 'N/A' }}" disabled />
                            </div>
                        </div>

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
                                <tbody class="bg-white divide-y divide-gray-200" x-ref="itemsTable">
                                    <template x-for="(item, index) in invoiceData.items" :key="index">
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-900 whitespace-nowrap">
                                                <input type="hidden" x-bind:name="'items[' + index + '][service_id]'" x-model="item.service_id">
                                                <input type="hidden" x-bind:name="'items[' + index + '][nombre_servicio]'" x-model="item.nombre_servicio">
                                                <input type="hidden" x-bind:name="'items[' + index + '][total_linea]'" x-model="item.total_linea">
                                                <p class="font-semibold" x-text="item.codigo + ' | ' + item.nombre_servicio"></p>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right whitespace-nowrap">
                                                <x-text-input
                                                    type="number"
                                                    x-bind:name="'items[' + index + '][cantidad]'"
                                                    x-model.number="item.cantidad"
                                                    @input="calculateTotals"
                                                    class="w-20 text-right"
                                                    min="1" required />
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right whitespace-nowrap">
                                                <x-text-input
                                                    type="number"
                                                    step="0.01"
                                                    x-bind:name="'items[' + index + '][precio_unitario_final]'"
                                                    x-model.number="item.precio_unitario_final"
                                                    @input="calculateTotals"
                                                    class="text-right w-28"
                                                    min="0.01" required />
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
                            </table>
                            <p class="mt-3 text-gray-600" x-show="invoiceData.items.length === 0">No se han añadido servicios a la boleta.</p>
                        </div>

                        {{-- boton para el nuevo servicio --}}
                        <div class="mb-8">
                            <button type="button" @click="$refs.serviceSelectModal.showModal()" class="inline-flex items-center px-4 py-2 font-bold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
                                <i class="mr-2 fa fa-plus-circle"></i> {{ __('Añadir Servicio') }}
                            </button>
                        </div>

                        {{-- calculos funales --}}
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
                        <div class="flex justify-end mt-8 space-x-4">
                            <x-primary-button type="submit" x-bind:disabled="invoiceData.items.length === 0" class="bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Actualizar Boleta') }}
                            </x-primary-button>
                        </div>
                    </form>

                    {{-- modal de seleccion del servicio --}}
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

{{-- logca en alpine --}}
<script>
    function invoiceForm() {
        const allServices = @json($services);
        const IVA_RATE = @json($iva_rate);
        const initialInvoice = @json($invoice->load('details'));
        const setting = @json($setting);

        // adaptar los detalles
        const initialItems = initialInvoice.details.map(detail => {
            const originalService = allServices.find(s => s.id === detail.service_id) || {};

            return {
                service_id: detail.service_id,
                codigo: originalService.codigo || 'N/A',
                nombre_servicio: detail.nombre_servicio,
                cantidad: parseFloat(detail.cantidad),
                precio_unitario_final: parseFloat(detail.precio_unitario_final),
                total_linea: parseFloat(detail.total_linea),
            };
        });

        return {
            IVA_RATE: IVA_RATE,
            serviceSearch: '',
            clientInfo: { name: '', phone: '', email: '', address: '' },

            invoiceData: {
                client_id: initialInvoice.client_id,
                fecha: initialInvoice.fecha,
                estado: initialInvoice.estado,
                metodo_pago: initialInvoice.metodo_pago,

                items: initialItems,
                subtotal: parseFloat(initialInvoice.subtotal),
                impuesto: parseFloat(initialInvoice.impuesto),
                total: parseFloat(initialInvoice.total),
            },

            init() {
                // sincronizar con la info del cliente
                const selectElement = document.getElementById('client_id');
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                this.updateClientInfo(selectedOption ? selectedOption.text : '');

                this.calculateTotals();
            },

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

            addItem(service) {
                const existingItemIndex = this.invoiceData.items.findIndex(item => item.service_id === service.id);

                if (existingItemIndex > -1) {
                    this.invoiceData.items[existingItemIndex].cantidad++;
                } else {
                    this.invoiceData.items.push({
                        service_id: service.id,
                        codigo: service.codigo,
                        nombre_servicio: service.nombre_servicio,
                        cantidad: 1,
                        precio_unitario_final: parseFloat(service.precio),
                        total_linea: parseFloat(service.precio),
                    });
                }

                this.serviceSearch = '';
                this.calculateTotals();
            },

            removeItem(index) {
                this.invoiceData.items.splice(index, 1);
                this.calculateTotals();
            },

            calculateTotals() {
                let subtotal = 0;
                this.invoiceData.items.forEach(item => {
                    const cantidad = parseFloat(item.cantidad) || 0;
                    const precio = parseFloat(item.precio_unitario_final) || 0;

                    const totalLinea = cantidad * precio;
                    item.total_linea = totalLinea;
                    subtotal += totalLinea;
                });

                this.invoiceData.subtotal = parseFloat(subtotal.toFixed(2));
                this.invoiceData.impuesto = parseFloat((subtotal * this.IVA_RATE).toFixed(2));
                this.invoiceData.total = parseFloat((this.invoiceData.subtotal + this.invoiceData.impuesto).toFixed(2));
            },

            updateClientInfo(selectedText) {
                const selectElement = document.getElementById('client_id');
                const selectedOption = selectElement.options[selectElement.selectedIndex];

                if (selectedOption.value) {
                    this.clientInfo.name = selectedText;
                    this.clientInfo.phone = selectedOption.getAttribute('data-phone');
                    this.clientInfo.email = selectedOption.getAttribute('data-email');
                    this.clientInfo.address = selectedOption.getAttribute('data-address');
                } else {
                    this.clientInfo = { name: '', phone: '', email: '', address: '' };
                }
            },

            formatCurrency(value) {
                const symbol = setting ? setting.simbolo_moneda : '$';
                return symbol + ' ' + (value ? value.toFixed(2) : (0).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },

            submitForm(event) {
                this.calculateTotals();
                event.target.submit();
            }
        }
    }
</script>
