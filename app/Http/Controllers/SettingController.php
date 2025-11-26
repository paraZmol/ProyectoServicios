<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // mostrar el formulario de edicion
    public function edit()
    {
        // obtener el primer registro
        $setting = Setting::firstOrNew(['id' => 1]);

        return view('settings.edit', compact('setting'));
    }

    // actualizar el registro
    public function update(Request $request)
    {
        // obtener la instancia
        $setting = Setting::firstOrNew(['id' => 1]);

        // validacion de datos
        $validatedData = $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo_electronico' => 'nullable|email|max:255',
            'iva_porcentaje' => 'required|numeric|min:0|max:100',
            'simbolo_moneda' => 'required|string|max:10',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'region_provincia' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:20',
            // ralga para el logo con un maximo de 2mb
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // subida de logo
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $path = $file->storeAs('public/logos', 'logo.' . $file->extension());

            // guardar el path
            $validatedData['logo_path'] = str_replace('public/', '', $path);
        }

        // guardar el ajuste
        $setting->fill($validatedData);
        $setting->save();

        return redirect()->route('settings.edit')->with('success', 'Configuración actualizada correctamente.');
    }
}
