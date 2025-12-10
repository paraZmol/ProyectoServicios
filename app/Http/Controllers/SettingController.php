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
            'region' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:20',
            // ralga para el logo con un maximo de 10mb
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
            // regla para que la subida del icono sea una maximo de 500kb
            'favicon_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:500',
            'ruc'=> 'nullable|string|max:20',
        ]);

        // subida de logo
        if ($request->hasFile('logo_file')) {
            // borarr la imagn anterio
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }

            $file = $request->file('logo_file');
            // nombre unico
            $filename = 'logo_' . time() . '.' . $file->extension();

            // ubicacion del logo
            //$path = $file->storeAs('logos', 'logo.' . $file->extension(), 'public');

            // guardar el path
            //$validatedData['logo_path'] = str_replace('public/', '', $path);
            $path = $file->storeAs('logos', $filename, 'public');
            $validatedData['logo_path']=$path;
        }

        //subida de icono
        if ($request->hasFile('favicon_file')) {
            // borrar el icono anterio
            if ($setting->favicon_path && Storage::disk('public')->exists($setting->favicon_path)) {
                Storage::disk('public')->delete($setting->favicon_path);
            }

            $file = $request->file('favicon_file');
            // nombre unico
            $filename = 'icon_' . time() . '.' . $file->extension();

            // ubicacion del icono
            //$path = $file->storeAs('logos', 'icon.' . $file->extension(), 'public');

            // guardar en el path
            $path = $file->storeAs('logos', $filename, 'public');
            $validatedData['favicon_path'] = $path;
        }

        // guardar el ajuste
        $setting->fill($validatedData);
        $setting->save();

        return redirect()->route('settings.edit')->with('success', 'Configuración actualizada correctamente.');
    }
}
