<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Diario - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header-container {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .company-info-table {
            width: 100%;
        }
        .company-info-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .logo-cell {
            width: 60px;
            padding-right: 10px;
        }
        .text-cell {
            text-align: left;
        }

        .report-info {
            text-align: right;
            vertical-align: top;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            color: #222;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .subtitle {
            font-size: 14px;
            color: #555;
            margin-top: 2px;
        }
        .company-logo {
            max-height: 50px;
            max-width: 100%;
            display: block;
        }

        /* resumen simple */
        .summary-box {
            width: 100%;
            margin-bottom: 25px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .summary-table {
            width: 100%;
        }
        .summary-item {
            text-align: center;
            border-right: 1px solid #ccc;
            padding: 0 5px;
        }
        .summary-item:last-child {
            border-right: none;
        }
        .s-label { font-size: 10px; text-transform: uppercase; color: #777; display: block; }
        .s-value { font-size: 16px; font-weight: bold; color: #000; display: block; margin-top: 4px; }
        .s-danger { color: #d32f2f; }
        .s-warning { color: #e6a23c; }
        .s-success { color: #0BA976; }

        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .main-table th {
            background-color: #333;
            color: #fff;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        .main-table td {
            border-bottom: 1px solid #eee;
            padding: 8px;
            vertical-align: middle;
        }
        .time-col { color: #666; font-size: 11px; width: 60px; text-align: center; }
        .num-col { color: #444; font-size: 11px; width: 40px; text-align: center; font-weight: bold; }
        .desc-col { font-weight: bold; }
        .price-col { text-align: right; width: 100px; font-weight: bold; }

        .service-list {
            font-size: 11px;
            color: #555;
            font-weight: normal;
            display: block;
            margin-top: 2px;
        }

        /* estilos para filas anuladas */
        .row-void td {
            background-color: #fff5f5;
            color: #aaa;
            text-decoration: line-through;
        }
        .tag-void {
            display: inline-block;
            background: #ffebee;
            color: #c62828;
            font-size: 9px;
            padding: 1px 4px;
            border-radius: 3px;
            margin-left: 5px;
            text-decoration: none !important;
        }

        /* estilos para filas pendientes */
        .row-pending td {
            background-color: #fffde7; /* amarillo suave */
            color: #555;
        }
        .tag-pending {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            font-size: 9px;
            padding: 1px 4px;
            border-radius: 3px;
            margin-left: 5px;
            text-decoration: none !important;
            border: 1px solid #ffeeba;
        }

    </style>
</head>
<body>

    <!-- encabezado -->
    <table class="header-container">
        <tr>
            <td style="text-align: left; width: 60%;">
                <!-- tabla anidada para alinear logo y texto -->
                <table class="company-info-table">
                    <tr>
                        <td class="logo-cell">
                            @if($setting && $setting->logo_path)
                                <img src="{{ public_path('storage/' . $setting->logo_path) }}" class="company-logo" alt="logo">
                            @endif
                        </td>
                        <td class="text-cell">
                            <div class="title">{{ $setting->nombre_empresa ?? 'EMPRESA' }}</div>
                            <div class="subtitle">RUC: {{ $setting->ruc ?? '---' }}</div>
                        </td>
                    </tr>
                </table>
            </td>

            <td class="report-info">
                <div class="title" style="font-size: 14px; color: #555;">REPORTE DIARIO</div>
                <div style="font-size: 12px; font-weight: bold; margin-top: 5px;">{{ $user->name }}</div>
                <div style="font-size: 11px; color: #777;">{{ \Carbon\Carbon::parse($fecha)->isoFormat('dddd, D MMMM YYYY') }}</div>
            </td>
        </tr>
    </table>

    <!-- resumen -->
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td class="summary-item">
                    <span class="s-label">Total Recaudado</span>
                    <span class="s-value s-success">{{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($resumen['total_dia'], 2) }}</span>
                </td>

                {{-- campo faltante por cobrar --}}
                <td class="summary-item">
                    <span class="s-label">Faltante (Por Cobrar)</span>
                    <span class="s-value s-warning">{{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($resumen['por_cobrar'], 2) }}</span>
                </td>

                <td class="summary-item">
                    <span class="s-label">Efectuadas</span>
                    {{-- ahora en verde --}}
                    <span class="s-value s-success">{{ $resumen['cantidad_efectuadas'] }}</span>
                </td>
                <td class="summary-item">
                    <span class="s-label">Pendientes</span>
                    {{-- ahora en amarillo --}}
                    <span class="s-value s-warning">{{ $resumen['cantidad_pendientes'] }}</span>
                </td>
                <td class="summary-item">
                    <span class="s-label">Anuladas</span>
                    <span class="s-value s-danger">{{ $resumen['cantidad_anuladas'] }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- tabla detalle -->
    <table class="main-table">
        <thead>
            <tr>
                <th style="text-align: center;">#</th>
                <th>Detalles de la venta</th>
                <th style="text-align: right;">Total / Ingreso</th>
                <th style="text-align: center;">Hora</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                {{-- color de fila --}}
                <tr class="{{ $invoice->estado == 'Anulada' ? 'row-void' : ($invoice->estado == 'Pendiente' ? 'row-pending' : '') }}">
                    <td class="num-col">
                        {{-- numeracion inversa --}}
                        {{ $invoices->count() - $loop->index }}
                    </td>

                    <!-- descripcion -->
                    <td class="desc-col">
                        @if($invoice->estado == 'Anulada')
                            <span class="tag-void">ANULADA</span>
                        @elseif($invoice->estado == 'Pendiente')
                            <span class="tag-pending">PENDIENTE</span>
                        @endif

                        <span style="color: #000;">Recibo #{{ $invoice->correlativo ?? $invoice->id }}</span>

                        <span class="service-list">
                            @foreach($invoice->details as $detail)
                                • {{ $detail->nombre_servicio }} (x{{ $detail->cantidad }})<br>
                            @endforeach
                        </span>
                    </td>

                    <!-- total -->
                    <td class="price-col">

                        @if($invoice->estado == 'Pendiente')
                            {{-- caso pendiente - muestra el monto pagado --}}
                            {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->monto_pagado, 2) }}

                            <br>
                            {{-- muestra el saldo pendiente por cobrar --}}
                            <span style="font-size: 10px; color: #d32f2f; font-weight: normal;">
                                (Por cobrar: {{ number_format($invoice->total - $invoice->monto_pagado, 2) }})
                            </span>
                        @else
                            {{-- caso normal pagada o anulada: muestra el total --}}
                            {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->total, 2) }}
                        @endif

                    </td>

                    <!-- hora -->
                    <td class="time-col">
                        {{ $invoice->created_at->format('H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 30px; color: #999;">
                        No tienes ventas registradas en esta fecha.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
