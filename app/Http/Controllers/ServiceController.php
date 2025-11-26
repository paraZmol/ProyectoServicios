<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\ServiceStoreRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function index(Request $request)
    {
        // 1. Obtener el término de búsqueda
        $search = $request->get('search');

        // 2. Construir la consulta
        $services = Service::query()
            ->when($search, function ($query, $search) {
                // Buscar por código o nombre (Producto)
                $query->where('codigo', 'like', '%' . $search . '%')
                      ->orWhere('nombre', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10); // Paginación de 10 elementos por página

        return view('services.index', compact('services', 'search'));
    }

    /**
     * Muestra el formulario para crear un nuevo servicio.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Almacena un servicio recién creado.
     */
    public function store(ServiceStoreRequest $request)
    {
        // Los datos ya están validados por ServiceStoreRequest
        Service::create($request->validated());

        return redirect()->route('services.index')->with('success', 'Servicio creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un servicio específico.
     */
    public function edit(Service $service)
    {
        // Laravel automáticamente inyecta la instancia de Service (Route Model Binding)
        return view('services.edit', compact('service'));
    }

    /**
     * Actualiza el servicio en el almacenamiento.
     */
    public function update(ServiceStoreRequest $request, Service $service)
    {
        // Los datos ya están validados
        $service->update($request->validated());

        return redirect()->route('services.index')->with('success', 'Servicio actualizado correctamente.');
    }

    /**
     * Elimina el servicio del almacenamiento.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        // Si se implementa un "estado" o Soft Delete, aquí se podría actualizar el estado.
        // Como no implementamos Soft Deletes, se elimina permanentemente.

        return redirect()->route('services.index')->with('success', 'Servicio eliminado correctamente.');
    }
}