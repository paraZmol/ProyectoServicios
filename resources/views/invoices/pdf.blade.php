<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Boleta #{{ $invoice->id }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        /* --- header --- */
        .header-container {
            width: 100%;
            margin-bottom: 15px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            padding: 0;
            vertical-align: middle;
        }

        /* logo */
        .logo {
            max-width: 150px;
            height: auto;
        }

        /* Contenedor de la información de la empresa */
        .company-info {
            text-align: center;
            padding: 0 10px;
            line-height: 1.5;
        }

        /* Estilo para el recuadro de N° de Boleta/RUC */
        .ruc-box {
            border: 2px solid #333;
            padding: 8px 5px;
            text-align: center;
            background-color: #f5f5f5;
        }
        .ruc-box h4 {
            margin: 0;
            font-size: 14px;
            padding-bottom: 5px;
            border-bottom: 1px solid #333;
        }
        .ruc-box p {
            margin: 2px 0;
        }

        /* --- Estilos Generales y Tablas --- */
        .divider {
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-top: 10px;
        }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .totals {
            width: 30%;
            float: right;
            margin-top: 20px;
        }
        .totals p {
            margin: 5px 0;
        }
        .totals h3 {
            border-top: 1px solid #333;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <table class="header-table">
            <tr>
                <td width="25%">
                    @if (isset($setting->logo_path) && $setting->logo_path)
                        {{-- Generar la ruta absoluta requerida por Dompdf --}}
                        @php
                            $logoPath = 'file://' . \Illuminate\Support\Facades\Storage::disk('public')->path($setting->logo_path);
                        @endphp
                        <img
                            src="{{ $logoPath }}"
                            alt="{{ $setting->razon_social ?? 'Logo de la Empresa' }}"
                            class="logo"
                        >
                    @else
                        <h2 style="margin: 0; font-size: 18px;">{{ $setting->razon_social ?? 'PROYECTO SERVICIOS S.A.' }}</h2>
                    @endif
                </td>

                <td width="50%" class="company-info">
                    <p style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">{{ $setting->razon_social ?? 'PROYECTO SERVICIOS S.A.' }}</p>
                    {{-- Asumiendo que tienes un campo RUC --}}
                    <p style="margin: 0;">RUC: **{{ $setting->ruc ?? '[RUC FALTANTE]' }}**</p>
                    <p style="margin: 0;">Dir: {{ $setting->direccion ?? 'Av. Central 123, Lima' }}</p>
                    <p style="margin: 0;">Telf: {{ $setting->telefono ?? '+51 987 654 321' }}</p>
                    <p style="margin: 0;">Email: {{ $setting->email ?? 'contacto@servicios.com' }}</p>
                </td>

                <td width="25%">
                    <div class="ruc-box">
                        <p style="font-weight: bold;">R.U.C. {{ $setting->ruc ?? '[RUC FALTANTE]' }}</p>
                        <h4 style="color: #c00;">BOLETA DE VENTA</h4>
                        <p>N°: {{ $invoice->serie ?? 'B001' }}-{{ str_pad($invoice->correlativo ?? $invoice->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <table width="100%" style="margin-bottom: 20px;">
        <tr>
            <td width="50%">
                <p style="font-weight: bold;">Cliente: {{ $invoice->client->nombre }}</p>
                <p>DNI: {{ $invoice->client->dni ?? 'Sin DNI' }}</p>
                <p>Dirección: {{ $invoice->client->direccion ?? 'Sin dirección' }}</p>
                <p>Email: {{ $invoice->client->email ?? 'Email no disponible' }}</p>
                <p>Teléfono: {{ $invoice->client->telefono ?? 'Telefono no disponible' }}</p>
            </td>
            <td width="50%" class="text-left">
                <p><strong>N° Boleta: #{{ $invoice->id }}</strong></p>
                <p>Fecha: {{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}</p>
                <p>Vendedor: {{ $invoice->user->name }}</p>
                <p>Estado: {{ $invoice->estado }}</p>
                <p>Método de Pago: {{ $invoice->metodo_pago }}</p>
            </td>
        </tr>
    </table>

    <table class="details-table" width="100%">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th class="text-left" width="50%">SERVICIO</th>
                <th class="text-right" width="15%">P. UNITARIO</th>
                <th class="text-right" width="10%">CANTIDAD</th>
                <th class="text-right" width="25%">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->details as $item)
            <tr>
                <td>{{ $item->nombre_servicio }}</td>
                <td class="text-right">{{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($item->precio_unitario_final, 2) }}</td>
                <td class="text-right">{{ $item->cantidad }}</td>
                <td class="text-right">{{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($item->total_linea, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right totals">
        <p>Subtotal: {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->subtotal, 2) }}</p>
        <p>Impuesto ({{ $setting->iva_porcentaje ?? '18.00' }}%): {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->impuesto, 2) }}</p>
        <h3>TOTAL: {{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->total, 2) }}</h3>
    </div>
</body>
</html>
