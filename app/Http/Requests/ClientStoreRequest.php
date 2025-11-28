<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('client') ?? null;

        return [
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => [
                'nullable',
                'email',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],
            'direccion' => 'nullable|string|max:255',
            'estado' => 'nullable|in:activo,inactivo',
            'dni' => [
                'nullable',
                'string',
                'max:20',
                'unique:clients,dni,' . $clientId,
            ],
        ];
    }
}
