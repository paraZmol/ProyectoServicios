<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // rango de fechas - hoy por defecto
        $startDate = $request->input('start_date', Carbon::now()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        // consultar ventas por rango
        $invoices = Invoice::with(['client', 'user'])
            ->whereBetween('fecha', [$startDate, $endDate])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // estadisticas - resumen
        // suma de pagos y adelantos
        $totalIngresos = $invoices->where('estado', 'Pagada')->sum('total')
                       + $invoices->where('estado', 'Pendiente')->sum('monto_pagado');

        $totalAnulado = $invoices->where('estado', 'Anulada')->sum('total');

        $cantidadVentas = $invoices->where('estado', '!=', 'Anulada')->count();

        return view('reports.index', compact(
            'invoices',
            'startDate',
            'endDate',
            'totalIngresos',
            'totalAnulado',
            'cantidadVentas'
        ));
    }
}
