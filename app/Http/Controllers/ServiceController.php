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

    // calcular el codigo del servicio
    protected function generateNextServiceCode()
    {
        // encontrar el ultimo servicio
        $latestService = Service::orderBy('id', 'desc')->first();

        $nextNumber = 1;

        if ($latestService) {
            // extraer del codigo existente
            $lastCode = $latestService->codigo;

            // capturar el numero final de cadena
            if (preg_match('/(\d+)$/', $lastCode, $matches)) {
                $lastNumber = (int)$matches[1];
                $nextNumber = $lastNumber + 1;
            } else {
                // usar el id +1
                $nextNumber = $latestService->id + 1;
            }
        }

        // formatear el numero
        return 'SVC' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // nuevo servicio
    public function create()
    {
        $setting = Setting::first();
        $nextCode = $this->generateNextServiceCode();
        //return view('services.create', compact('setting'));
        return view('services.create', compact('setting', 'nextCode'));
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
        $setting = Setting::first();
        // instancia de servicio
        return view('services.edit', compact('service', 'setting'));
        // instancia de servicio
        //return view('services.edit', compact('service'));
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

    // eliminar permanente de forma total
    public function forceDelete($id)
    {
        // busqueda de servicio
        $service = Service::withTrashed()->findOrFail($id);

        try {
            // buscar servicio desvinculado
            $dummyService = Service::withTrashed()
                            ->where('nombre_servicio', 'SERVICIO DESVINCULADO')
                            ->first();

            // creacion de servicio ficticio si no existe
            if (!$dummyService) {
                $dummyService = Service::create([
                    'codigo'          => 'SVC-000', // codigo especial
                    'nombre_servicio' => 'SERVICIO DESVINCULADO',
                    'precio'          => 0.00
                ]);
            }

            // asegurar que no este eliminado
            if ($dummyService->trashed()) {
                $dummyService->restore();
            }

            // reasignar detalles de boletas
            if (method_exists($service, 'invoiceDetails')) {
                // actualizar al servicio desvinculado
                $service->invoiceDetails()->withTrashed()->update(['service_id' => $dummyService->id]);
            }

            // tambien chequear si se llama items
            if (method_exists($service, 'items')) {
                $service->items()->withTrashed()->update(['service_id' => $dummyService->id]);
            }

            // eliminacion total del servicio original
            $service->forceDelete();

            return redirect()->route('services.deleted')
                ->with('success', 'Servicio eliminado permanentemente. Historial transferido a "SERVICIO DESVINCULADO".');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => 'Error al procesar la eliminación: ' . $e->getMessage()]);
        }
    }
}
