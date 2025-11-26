<?php
// app/Http/Controllers/ClientController.php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\ClientStoreRequest;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Muestra la lista de clientes con paginación y búsqueda.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $clients = Client::query()
            ->when($search, function ($query, $search) {
                // Búsqueda por nombre o correo electrónico
                $query->where('nombre', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
            })
            // El estado puede ser nulo, pero ordenamos por nombre
            ->orderBy('nombre', 'asc')
            ->paginate(10);

        return view('clients.index', compact('clients', 'search'));
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Almacena un cliente recién creado.
     */
    public function store(ClientStoreRequest $request)
    {
        // Los datos ya están validados por ClientStoreRequest
        Client::create($request->validated());

        return redirect()->route('clients.index')->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un cliente específico.
     */
    public function edit(Client $client)
    {
        // Route Model Binding inyecta la instancia del cliente
        return view('clients.edit', compact('client'));
    }

    /**
     * Actualiza el cliente en el almacenamiento.
     */
    public function update(ClientStoreRequest $request, Client $client)
    {
        // Los datos ya están validados
        $client->update($request->validated());

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Elimina el cliente del almacenamiento.
     */
    public function destroy(Client $client)
    {
        // Nota: Eliminar un cliente con facturas asociadas fallará si no usamos Soft Deletes,
        // pero la migración está configurada para eliminar las facturas en cascada, lo cual es peligroso.
        // Mejor dejarlo así por ahora y luego cambiar a Soft Deletes si es necesario.
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente eliminado correctamente.');
    }
}