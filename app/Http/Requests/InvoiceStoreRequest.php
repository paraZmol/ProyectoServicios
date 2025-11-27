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
            // Validación de campos principales de la boleta
            'client_id' => 'required|exists:clients,id',
            'fecha' => 'required|date',
            'metodo_pago' => 'required|string|max:50',
            'estado' => 'required|in:Pendiente,Pagada,Cancelada',

            //CORRECCIÓN CLAVE: Estos campos calculados son obligatorios
            'subtotal' => 'required|numeric|min:0',
            'impuesto' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',

            // Validación de ítems (Detalles de la boleta)
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',

            // CORRECCIÓN: nombre_servicio es obligatorio
            'items.*.nombre_servicio' => 'required|string|max:255',

            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario_final' => 'required|numeric|min:0.01',

            // CORRECCIÓN: total_linea es el campo que la BD espera y el formulario envia
            'items.*.total_linea' => 'required|numeric|min:0.01',
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
