<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // validacion de campos
            'client_id' => 'required|exists:clients,id',
            'fecha' => 'required|date',
            'metodo_pago' => 'required|string|max:50',
            'estado' => 'required|in:Pendiente,Pagada,Cancelada',
            'total' => 'required|numeric|min:0',

            // validacion de items
            'items' => 'required|array|min:1', // item minimo
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0.01',
            'items.*.subtotal' => 'required|numeric|min:0.01',
        ];
    }

    // mensajes de error
    public function messages()
    {
        return [
            'client_id.required' => 'Debe seleccionar un cliente.',
            'items.required' => 'La factura debe contener al menos un producto/servicio.',
            'items.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
            'items.*.service_id.required' => 'Cada línea debe tener un servicio asociado.',
        ];
    }
}