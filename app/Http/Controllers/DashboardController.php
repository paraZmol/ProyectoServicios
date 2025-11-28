<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Setting;

class DashboardController extends Controller
{
    // vista del dash
    public function index()
    {
        // obtener los datos
        $setting = Setting::firstOrNew(['id' => 1]);

        // top 5 ultimas ventas
        $ultimasVentas = Invoice::latest()
            ->with('client')
            ->take(5)
            ->get();

        // top de los 3 servicios mas solicitados
        $serviciosMasVendidos = InvoiceDetail::select('nombre_servicio')
            ->selectRaw('SUM(cantidad) as total_vendido')
            ->groupBy('nombre_servicio')
            ->orderByDesc('total_vendido')
            ->take(3)
            ->get();

        return view('dashboard', compact('setting', 'ultimasVentas', 'serviciosMasVendidos'));
    }
}
