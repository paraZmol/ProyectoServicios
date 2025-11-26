<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Esto permite que el usuario autenticado (logged in) ejecute esta solicitud.
        return true;
    }

    public function rules(): array
    {
        // Las reglas de validación basadas en la estructura del modelo Service
        return [
            'codigo' => 'required|string|max:50|unique:services,codigo,' . $this->route('service'),
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0.01',
        ];
    }

    // Opcional: mensajes de error personalizados
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