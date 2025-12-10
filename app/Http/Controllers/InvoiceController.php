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
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;


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

        //$clients = Client::where('estado', 'activo')->get(['id', 'nombre', 'telefono', 'email', 'direccion']);


        $services = Service::all(['id', 'codigo', 'nombre_servicio', 'precio']);

        $iva_rate = $setting ? $setting->iva_porcentaje / 100 : 0.13;
        $invoice = new Invoice(); // instancia vasia para el formulario

        return view('invoices.create', compact( 'services', 'iva_rate', 'setting', 'invoice'));
    }

    // guardar boleta
    public function store(InvoiceStoreRequest $request)
    {
        //Log::info("=== INICIO STORE DE INVOICE ===");

        //DB::beginTransaction();

        try {

            //Log::info("Datos recibidos del formulario", $request->all());

            $data = $request->validated();

            //Log::info("Datos validados", $data);

            // cabecera de boleta

            //Log::info("Creando cabecera de factura...");

            // para asignar el monto pagado en caso de pendiente
            if ($data['estado'] === 'Pagada') {
                // en caso de estar pagado, entonces el monto pagado es el mismo que el total
                $data['monto_pagado'] = $data['total'];
            } elseif ($data['estado'] === 'Anulada') {
                // si esta anulada, entonces no hay pago
                $data['monto_pagado'] = 0;
            } else {
                // en caso de estar en pendiente, se recoge el valor de input, y si es que no viene nada entonces es 0
                $data['monto_pagado'] = $data['monto_pagado'] ?? 0;
            }

            $invoice = Invoice::create([
                'client_id' => $data['client_id'],
                'user_id' => Auth::id(),
                'fecha' => $data['fecha'],
                'metodo_pago' => $data['metodo_pago'],
                'estado' => $data['estado'],
                'subtotal' => $data['subtotal'],
                'impuesto' => $data['impuesto'],
                'total' => $data['total'],
                'monto_pagado' => $data['monto_pagado'],
            ]);

            //Log::info("Cabecera creada correctamente", ['invoice_id' => $invoice->id]);

            // detalles

            //Log::info("Procesando detalle de items...");

            $itemsData = collect($data['items'])->map(function ($item) use ($invoice) {

                //Log::info("Item procesado", $item);

                return [
                    'invoice_id' => $invoice->id,
                    'service_id' => $item['service_id'],
                    'nombre_servicio' => $item['nombre_servicio'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario_final' => $item['precio_unitario_final'],
                    'total_linea' => $item['total_linea'],
                ];
            });

            //Log::info("Guardando detalles en la BD...");

            $invoice->details()->createMany($itemsData->toArray());

            //Log::info("Detalles guardados correctamente.");

            DB::commit();

            //Log::info("=== STORE COMPLETADO SIN ERRORES ===");

            return redirect()->route('invoices.show', $invoice->id)
                ->with('success', 'Boleta creada exitosamente con el número #' . $invoice->id);

        } catch (\Exception $e) {

            /*Log::error("ERROR EN STORE", [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);*/

            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Ocurrió un error al guardar la boleta: ' . $e->getMessage());
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
        $services = Service::all(['id', 'codigo', 'nombre_servicio', 'precio']);

        $iva_rate = $setting ? $setting->iva_porcentaje / 100 : 0.18;

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

            // para el caso de pendiente
            if ($data['estado'] === 'Pagada') {
                $data['monto_pagado'] = $data['total'];
            } elseif ($data['estado'] === 'Anulada') {
                $data['monto_pagado'] = 0;
            } else {
                $data['monto_pagado'] = $data['monto_pagado'] ?? 0;
            }

            // actualizar header
            $invoice->update([
                'client_id' => $data['client_id'],
                'fecha' => $data['fecha'],
                'metodo_pago' => $data['metodo_pago'],
                'estado' => $data['estado'],
                'subtotal' => $data['subtotal'],
                'impuesto' => $data['impuesto'],
                'total' => $data['total'],
                'monto_pagado' => $data['monto_pagado'],
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

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Boleta #' . $invoice->id . ' eliminada exitosamente.');
    }

    // ver lo eliminados
    public function deleted()
    {
        $deletedInvoices = Invoice::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('invoices.deleted_index', [
            'invoices' => $deletedInvoices,
        ]);
    }

    // restaurar boleta
    public function restore($id)
    {
        // encontrar por id
        $invoice = Invoice::onlyTrashed()->findOrFail($id);

        // restaurar
        $invoice->restore();

        return redirect()->route('invoices.deleted')->with('success', '✅ Boleta N°' . $invoice->id . ' restaurada con éxito.');
    }

    // pdf
    public function generatePdf(Invoice $invoice) {
        // cargar la relaciones
        $invoice->load('client', 'user', 'details.service');
        $setting = Setting::first();

        // html de vista para la boleta
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'setting'));

        // nombre del archivo
        $filename = 'Boleta_' . $invoice->id . '_' . $invoice->client->nombre . '.pdf' ;

        // mostrar en navegador
        return $pdf->stream($filename);

        // en caso de quere descargar de forma directa, es decir que se descarga antes de visualizar
        //return $pdf->download($filename);
    }

    // ticket
    public function ticket(Invoice $invoice)
    {
        // relacions
        $invoice->load('client', 'user', 'details.service');

        $setting = Setting::latest()->first();

        // vista especial para el ticket
        return view('invoices.ticket', compact('invoice', 'setting'));
    }

    // eliminacion permanente
    public function forceDelete($id)
    {
        // encontrar boleta
        $invoice = Invoice::withTrashed()->find($id);

        if (!$invoice) {
            return redirect()->back()->with('error', 'Boleta no encontrada.');
        }

        DB::beginTransaction();

        try {
            // eliminar los detalles de la boleta
            $invoice->details()->withTrashed()->forceDelete();

            // eliminar la boleta
            $invoice->forceDelete();

            DB::commit();

            return redirect()->route('invoices.deleted')
                ->with('success', 'Boleta eliminada permanentemente. No queda rastro.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}
