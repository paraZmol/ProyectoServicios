<?php

namespace App\Http\Requests;

//use Dotenv\Exception\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class InvoiceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        Log::info('Log: autorizacion del invoice update request exitosa');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        Log::info('log: datos del formuulario de actulizacion enviados para validacion', $this->all());
        return [
            'client_id' => 'required|exists:clients,id',
            'fecha' => 'required|date',
            'metodo_pago' => 'required|string|max:50',
            'estado' => 'required|in:Pagada,Pendiente,Anulada',

            // calculos generales
            'subtotal' => 'required|numeric|min:0',
            'impuesto' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',

            // validacion de items
            'items' => 'required|array|min:1', // minimo un item
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.nombre_servicio' => 'required|string|max:255',
            'items.*.cantidad' => 'required|numeric|min:1',
            'items.*.precio_unitario_final' => 'required|numeric|min:0.01',
            'items.*.total_linea' => 'required|numeric|min:0.01',
        ];
    }

    public function messages(): array
    {
        return [
            'items.min' => 'La factura debe contener al menos un servicio.',
            'items.*.cantidad.min' => 'La cantidad de un servicio debe ser al menos 1.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Log::warning('log: fallo en la validacion del invoice update request. Errores: ', $validator->errors()->all());
        throw new ValidationException($validator);
    }
}