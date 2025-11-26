<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // logear con usuario autenticado
        return true;
    }

    public function rules(): array
    {
        $serviceId = $this->route('service') ?? null;
        // reglas de validacion
        return [
            'codigo' => [
                'required',
                'string',
                'max:50' ,
                Rule::unique('services', 'codigo')->ignore($serviceId),
            ],
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0.01',
        ];
    }

    // mensajes de error
    public function messages()
    {
        return [
            'codigo.required' => 'El campo código es obligatorio.',
            'codigo.unique' => 'Este código ya ha sido registrado.',
            'nombre.required' => 'El campo nombre (producto/servicio) es obligatorio.',
            'precio.required' => 'El campo precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',
        ];
    }
}
