<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-table {
            width: 100%;
        }
        /* Estilos para alinear Logo y Texto */
        .inner-header-table {
            width: 100%;
        }
        .inner-header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .logo-cell {
            width: 60px; /* Ancho fijo para el logo */
            padding-right: 10px;
        }
        .logo {
            max-height: 50px;
            max-width: 60px;
            display: block;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .company-sub {
            font-size: 12px;
            margin-top: 2px;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }

        /* Cajas de Resumen */
        .summary-box {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 8px;
            text-align: center;
            border-right: 1px solid #ddd;
        }
        .summary-table td:last-child {
            border-right: none;
        }
        .s-label {
            display: block;
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
        }
        .s-value {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-top: 4px;
        }

        /* Tabla de Datos */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background-color: #253891; /* Azul corporativo */
            color: white;
            padding: 6px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        .data-table td {
            border-bottom: 1px solid #eee;
            padding: 8px 5px; /* Un poco más de padding vertical */
            vertical-align: top; /* Alinear al tope para listas largas */
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        .service-list {
            font-size: 10px;
            color: #555;
            margin-top: 3px;
            line-height: 1.3;
        }

        .tag-void {
            color: #c62828;
            font-weight: bold;
            font-size: 10px;
            margin-right: 5px;
        }

        .tag-pending {
            color: #e6a23c;
            font-weight: bold;
            font-size: 10px;
            margin-right: 5px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 9px;
            text-align: center;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td width="60%" valign="middle">
                    {{-- Tabla anidada para alineación perfecta Logo-Texto --}}
                    <table class="inner-header-table">
                        <tr>
                            <td class="logo-cell">
                                @if($setting && $setting->logo_path)
                                    <img src="{{ public_path('storage/' . $setting->logo_path) }}" class="logo">
                                @endif
                            </td>
                            <td>
                                <div class="company-name">{{ $setting->nombre_empresa ?? 'MI EMPRESA' }}</div>
                                <div class="company-sub">RUC: {{ $setting->ruc ?? '---' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="40%" align="right" valign="top">
                    <div class="report-title">REPORTE HISTÓRICO DE VENTAS</div>
                    <div style="margin-top: 5px;">
                        Desde: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong><br>
                        Hasta: <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Resumen Ejecutivo --}}
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>
                    <span class="s-label">Ingresos Totales</span>
                    <span class="s-value">{{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($totalIngresos, 2) }}</span>
                </td>
                <td>
                    <span class="s-label">Ventas Válidas</span>
                    <span class="s-value">{{ $cantidadVentas }}</span>
                </td>
                <td>
                    <span class="s-label">Fecha de Impresión</span>
                    <span class="s-value">{{ date('d/m/Y H:i') }}</span>
                </td>
                <td>
                    <span class="s-label">Generado Por</span>
                    <span class="s-value">{{ Auth::user()->name }}</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- Tabla de Ventas --}}
    <table class="data-table">
        <thead>
            <tr>
                {{-- Columnas ajustadas según solicitud --}}
                <th width="12%">Fecha</th>
                <th width="50%">Detalle</th> {{-- Espacio amplio para servicios y recibo --}}
                <th width="20%">Vendedor</th>
                <th width="18%" class="text-right">Total / Ingreso</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    {{-- FECHA --}}
                    <td>{{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}</td>

                    {{-- DETALLE (Reemplaza N° Boleta y Estado) --}}
                    <td>
                        {{-- Etiquetas de estado visuales junto al título --}}
                        @if($invoice->estado == 'Anulada')
                            <span class="tag-void">[ANULADA]</span>
                        @elseif($invoice->estado == 'Pendiente')
                            <span class="tag-pending">[PENDIENTE]</span>
                        @endif

                        <span class="font-bold">Recibo #{{ $invoice->correlativo ?? str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</span>

                        <div class="service-list">
                            @foreach($invoice->details as $detail)
                                • {{ $detail->nombre_servicio }} (x{{ $detail->cantidad }})<br>
                            @endforeach
                        </div>
                    </td>

                    {{-- VENDEDOR --}}
                    <td>{{ Str::limit($invoice->user->name ?? 'Sistema', 25) }}</td>

                    {{-- TOTAL / INGRESO (Lógica condicional) --}}
                    <td class="font-bold text-right">
                        @if($invoice->estado == 'Pendiente')
                            {{-- Solo lo cobrado --}}
                            {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->monto_pagado, 2) }}
                            <br>
                            <span style="font-size: 9px; color: #d32f2f; font-weight: normal;">
                                (Por cobrar: {{ number_format($invoice->total - $invoice->monto_pagado, 2) }})
                            </span>
                        @elseif($invoice->estado == 'Anulada')
                            {{-- Tachado si anulada --}}
                            <span style="text-decoration: line-through; color: #999;">
                                {{ number_format($invoice->total, 2) }}
                            </span>
                        @else
                            {{-- Total completo si pagada --}}
                            {{ number_format($invoice->total, 2) }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px; color: #777;">
                        No se encontraron ventas en este rango de fechas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Página <span class="pagenum"></span> - Sistema de Gestión v1.0
    </div>

</body>
</html>
