<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class InvoiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        Log::info('LOG: Autorización del InvoiceStoreRequest exitosa.');
        return true;
    }

    public function rules(): array
    {
        Log::info('LOG: Datos del Formulario enviados para validación:', $this->all());
        return [
            //validacion de campos
            'client_id' => 'required|exists:clients,id',
            'fecha' => 'required|date',
            'metodo_pago' => 'required|string|max:50',
            'estado' => 'required|in:Pendiente,Pagada,Cancelada',

            //calculos
            'subtotal' => 'required|numeric|min:0',
            'impuesto' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',

            // validacion de los items
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',

            'items.*.nombre_servicio' => 'required|string|max:255',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario_final' => 'required|numeric|min:0.01',
            'items.*.total_linea' => 'required|numeric|min:0.01',
        ];
    }

    // mensajes de error
    public function messages()
    {
        //Log::warning('LOG: Fallo en la validación de InvoiceStoreRequest.', $this->validator->errors()->all());
        return [
            'client_id.required' => 'Debe seleccionar un cliente.',
            'items.required' => 'La factura debe contener al menos un producto/servicio.',
            'items.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
            'items.*.service_id.required' => 'Cada línea debe tener un servicio asociado.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // si la validaciopn fallaa
        Log::warning('LOG: Fallo en la validación de InvoiceStoreRequest. Errores:', $validator->errors()->all());

        throw new ValidationException($validator);
    }
}
