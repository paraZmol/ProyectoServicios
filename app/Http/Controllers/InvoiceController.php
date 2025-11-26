<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Service;
use App\Models\Setting;
use App\Http\Requests\InvoiceStoreRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    // ver lsita de boelrtas
    public function index(Request $request)
    {
        $search = $request->get('search');

        $invoices = Invoice::query()
            // cargamos los datos
            ->with('client', 'user')
            ->when($search, function ($query, $search) {
                // busqueda por id de factura, nombre de cliente o usuario
                $query->where('id', $search)
                      ->orWhereHas('client', function ($q) use ($search) {
                           $q->where('nombre', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('user', function ($q) use ($search) {
                           $q->where('name', 'like', '%' . $search . '%');
                      });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        // setinf para el simbolo de moneda
        $setting = Setting::first();

        return view('invoices.index', compact('invoices', 'search', 'setting'));
    }

    // ver el formulario de factura
    public function create()
    {
        $setting = Setting::first();

        $clients = Client::where('estado', 'activo')->get(['id', 'nombre', 'telefono', 'email', 'direccion']);
        $services = Service::all(['id', 'codigo', 'nombre', 'precio']);

        $iva_rate = $setting ? $setting->iva_porcentaje / 100 : 0.13;
        $invoice = new Invoice(); // instancia vasia para el formulario

        return view('invoices.create', compact('clients', 'services', 'iva_rate', 'setting', 'invoice'));
    }

    // guardar boleta
    public function store(InvoiceStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // cabecera de boleta
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

            // items de la boleta
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

            $invoice->details()->createMany($itemsData->toArray());

            DB::commit();

            return redirect()->route('invoices.show', $invoice->id)->with('success', 'Boleta creada exitosamente con el número #' . $invoice->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al guardar la boleta: ' . $e->getMessage());
        }
    }

    // mostrar un boleta espesifica
    public function show(Invoice $invoice)
    {
        $setting = Setting::first();
        // cargar la relaciones para el detalle
        $invoice->load('details', 'client', 'user');

        return view('invoices.show', compact('invoice', 'setting'));
    }

    // editar
    public function edit(Invoice $invoice)
    {
        $setting = Setting::first();

        $clients = Client::where('estado', 'activo')->get(['id', 'nombre', 'telefono', 'email', 'direccion']);
        $services = Service::all(['id', 'codigo', 'nombre', 'precio']);

        $iva_rate = $setting ? $setting->iva_porcentaje / 100 : 0.13;

        // Cargar detalles para pre-cargar el formulario Alpine
        $invoice->load('details');

        return view('invoices.edit', compact('clients', 'services', 'iva_rate', 'setting', 'invoice'));
    }

    // guardar actualizacion
    public function update(InvoiceUpdateRequest $request, Invoice $invoice)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // actualizar header
            $invoice->update([
                'client_id' => $data['client_id'],
                'fecha' => $data['fecha'],
                'tipo_pago' => $data['tipo_pago'],
                'estado' => $data['estado'],
                'subtotal' => $data['subtotal'],
                'impuesto' => $data['impuesto'],
                'total' => $data['total'],
            ]);

            //eliminar items existentes
            $invoice->details()->delete();

            // nuevos items con la info del form
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

            $invoice->details()->createMany($itemsData->toArray());

            DB::commit();

            return redirect()->route('invoices.show', $invoice->id)->with('success', 'Boleta #' . $invoice->id . ' actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar la boleta: ' . $e->getMessage());
        }
    }

    // eliminar factura
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Boleta #' . $invoice->id . ' eliminada exitosamente.');
    }
}
