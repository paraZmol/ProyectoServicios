<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\ServiceStoreRequest;
use Illuminate\Http\Request;
use App\Models\Setting;

class ServiceController extends Controller
{

    public function index(Request $request)
    {
        // obtener el temin de busqueda
        $search = $request->get('search');

        // consulta de busqueda
        $services = Service::query()
            ->when($search, function ($query, $search) {
                // por codigo o nombre
                $query->where('codigo', 'like', '%' . $search . '%')
                      ->orWhere('nombre_servicio', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10); // paginacion de 10

        $setting = Setting::first();

        return view('services.index', compact('services', 'search', 'setting'));
    }

    // nuevo servicio
    public function create()
    {
        return view('services.create');
    }

    // save nuevo servicio
    public function store(ServiceStoreRequest $request)
    {
        // validacion de datos
        Service::create($request->validated());

        return redirect()->route('services.index')->with('success', 'Servicio creado correctamente.');
    }

    // modificar servicio
    public function edit(Service $service)
    {
        // instancia de servicio
        return view('services.edit', compact('service'));
    }

    // guardar la modificacion
    public function update(ServiceStoreRequest $request, Service $service)
    {
        // validacion de datos
        $service->update($request->validated());

        return redirect()->route('services.index')->with('success', 'Servicio actualizado correctamente.');
    }

    // eliminar
    public function destroy(Service $service)
    {
        $service->delete();

        /*
        * avergitruar los soft deletes en laravel */

        return redirect()->route('services.index')->with('success', 'Servicio eliminado correctamente.');
    }

    public function deleted()
    {
        // mostrar los eliminados
        $deletedServices = Service::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('services.deleted_index', [
            'services' => $deletedServices,
        ]);
    }

    // restaurar servicios
    public function restore($id)
    {
        // encontrar opr id
        $service = Service::onlyTrashed()->findOrFail($id);

        // restaurar
        $service->restore();

        return redirect()->route('services.deleted')->with('success', '✅ Servicio "' . $service->nombre_servicio . '" restaurado con éxito.');
    }
}
