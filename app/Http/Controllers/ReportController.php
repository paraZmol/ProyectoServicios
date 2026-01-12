<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // capturar filtros
        $startDate = $request->input('start_date', Carbon::now()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
        $userId = $request->input('user_id'); // cambio aqui - filtro usuario
        $status = $request->input('status');   // cambio aqui - filtro estado

        //obtener lista de usuarios para el select
        $users = User::all();

        // construir la consulta base
        $query = Invoice::with(['client', 'user'])
            ->whereBetween('fecha', [$startDate, $endDate]);

        // aplicar filtros condicionales
        if ($userId) {
            $query->where('user_id', $userId);
        }
        if ($status) {
            $query->where('estado', $status);
        }

        // ejecutar consulta
        $invoices = $query->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();

        // calcular totales - basados en los resultados filtrados
        $totalIngresos = $invoices->where('estado', 'Pagada')->sum('total')
                       + $invoices->where('estado', 'Pendiente')->sum('monto_pagado');

        $totalAnulado = $invoices->where('estado', 'Anulada')->sum('total');

        $cantidadVentas = $invoices->where('estado', '!=', 'Anulada')->count();

        return view('reports.index', compact(
            'invoices',
            'startDate',
            'endDate',
            'users',      // pasar usuarios a la vista
            'userId',     // mantener filtro seleccionado
            'status',     // mantener filtro seleccionado
            'totalIngresos',
            'totalAnulado',
            'cantidadVentas'
        ));
    }

    // generar pdf
    public function downloadPdf(Request $request)
    {
        // replica de filtros en el imprible
        $startDate = $request->input('start_date', Carbon::now()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
        $userId = $request->input('user_id');
        $status = $request->input('status');

        $setting = Setting::first(); // datos de la empresa

        $query = Invoice::with(['client', 'user'])
            ->whereBetween('fecha', [$startDate, $endDate]);

        if ($userId) {
            $query->where('user_id', $userId);
        }
        if ($status) {
            $query->where('estado', $status);
        }

        $invoices = $query->orderBy('fecha', 'asc')->get();

        // totales para el pdf
        $totalIngresos = $invoices->where('estado', 'Pagada')->sum('total')
                       + $invoices->where('estado', 'Pendiente')->sum('monto_pagado');
        $cantidadVentas = $invoices->where('estado', '!=', 'Anulada')->count();

        $pdf = Pdf::loadView('reports.pdf', compact(
            'invoices', 'setting', 'startDate', 'endDate', 'totalIngresos', 'cantidadVentas'
        ));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('reporte_ventas_' . $startDate . '_al_' . $endDate . '.pdf');
    }
}
