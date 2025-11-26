<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Service;
use App\Models\Setting;
use App\Http\Requests\InvoiceStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    // lista de facturas
    public function index(Request $request)
    {
        $search = $request->get('search');

        $invoices = Invoice::query()
            // buscar por nombre al cliente
            ->with('client')
            ->when($search, function ($query, $search) {
                // buscar por id de factura o nombre de cliente
                $query->where('id', $search)
                      ->orWhereHas('client', function ($q) use ($search) {
                           $q->where('nombre', 'like', '%' . $search . '%');
                      });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('invoices.index', compact('invoices', 'search'));
    }

    // nueva factura
    public function create()
    {
        // configuracion de empresa
        $setting = Setting::first();

        // datos para la vista
        $clients = Client::where('estado', 'activo')->get(['id', 'nombre', 'telefono', 'email', 'direccion']);
        $services = Service::all(['id', 'codigo', 'nombre', 'precio']);

        // porcentaje de iva
        $iva_rate = $setting ? $setting->iva_porcentaje / 100 : 0.13;

        return view('invoices.create', compact('clients', 'services', 'iva_rate', 'setting'));
    }

    // guardar factura
    public function store(InvoiceStoreRequest $request)
    {
        // iniciar trasaccion
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // cabecera de la factura
            $invoice = Invoice::create([
                'client_id' => $data['client_id'],
                'user_id' => Auth::id(),
                'fecha' => $data['fecha'],
                'tipo_pago' => $data['tipo_pago'],
                'estado' => $data['estado'],
                'subtotal' => $data['subtotal'],
                'impuesto' => $data['impuesto'],
                'total' => $data['total'],
            ]);

            // crear items de factura
            $itemsData = collect($data['items'])->map(function ($item) use ($invoice) {
                return [
                    'invoice_id' => $invoice->id,
                    'service_id' => $item['service_id'],
                    'nombre_servicio' => $item['nombre_servicio'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario_final' => $item['precio_unitario_final'],
                    'total_linea' => $item['total_linea'],
                ];
            });

            // relacion details
            $invoice->details()->createMany($itemsData->toArray());

            // confirmar transaccion
            DB::commit();

            return redirect()->route('invoices.index')->with('success', 'Factura creada exitosamente con el número #' . $invoice->id);

        } catch (\Exception $e) {
            // revertir transaccion
            DB::rollBack();

            // para revertir a la pagina anterior con los datos ingresados para que no se pierdan
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al guardar la factura: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
