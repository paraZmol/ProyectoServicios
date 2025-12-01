<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\ClientStoreRequest;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // lista de cliente con pag y busqueda
    public function index(Request $request)
    {
        $search = $request->get('search');

        $clients = Client::query()
            ->when($search, function ($query, $search) {
                // busqueda por nombre o email
                $query->where('nombre', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('dni', 'like', '%' . $search . '%');
            })
            // orden por nombre
            ->orderBy('nombre', 'asc')
            ->paginate(10);

        return view('clients.index', compact('clients', 'search'));
    }

    // form nuevo cliente - mostrar
    public function create()
    {
        return view('clients.create');
    }

    // guardar el nuevo cliente
    public function store(ClientStoreRequest $request)
    {
        // validar datis
        Client::create($request->validated());

        return redirect()->route('clients.index')->with('success', 'Cliente creado correctamente.');
    }

    // editar cliente
    public function edit(Client $client)
    {
        // Route Model Binding inyecta la instancia del cliente
        return view('clients.edit', compact('client'));
    }

    // guardar la edicion
    public function update(ClientStoreRequest $request, Client $client)
    {
        // validacion de datos
        $client->update($request->validated());

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado correctamente.');
    }

    // eliminar cliente
    public function destroy(Client $client)
    {
        // nota - modificar la migracion y bd para no eliminar en cascada
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente eliminado correctamente.');
    }

    // ver clientes eliminados
    public function deleted(Request $request)
    {
        // para mostrar clientes eliminados
        $deletedClients = Client::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('clients.deleted_index', [
            'clients' => $deletedClients,
        ]);
    }

    // restaurar un cliente
    public function restore($id)
    {
        // encontrar al cliente eliminado por id
        $client = Client::onlyTrashed()->findOrFail($id);

        // restaurar
        $client->restore();

        return redirect()->route('clients.deleted')->with('success', '✅ Cliente "' . $client->nombre . '" restaurado con éxito. Ahora está activo en el listado principal.');
    }
}