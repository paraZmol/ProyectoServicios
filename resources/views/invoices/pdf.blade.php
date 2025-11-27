<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Boleta #{{ $invoice->id }}</title>

    <style>
        /* Estilos CSS BÁSICOS para DomPDF */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header div {
            width: 50%;
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
        /* Para el logo: DomPDF requiere la ruta completa. */
        #logo {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="text-left">
            <p style="font-size: 16px; font-weight: bold;">{{ $setting->razon_social ?? 'PROYECTO SERVICIOS S.A.' }}</p>
            <p>{{ $setting->direccion ?? 'Av. Central 123, Lima' }}</p>
            <p>{{ $setting->telefono ?? '+51 987 654 321' }} | {{ $setting->email ?? 'contacto@servicios.com' }}</p>
        </div>
        <div class="text-right">
            <img src="{{ public_path('img/logo.png') }}" alt="Logo Empresa" id="logo">
        </div>
    </div>

    <table width="100%" style="margin-bottom: 20px;">
        <tr>
            <td width="50%">
                <p style="font-weight: bold;">Cliente: {{ $invoice->client->nombre }}</p>
                <p>Dirección: {{ $invoice->client->direccion ?? 'Sin dirección' }}</p>
                <p>Email: {{ $invoice->client->email ?? 'Email no disponible' }}</p>
                <p>Teléfono: {{ $invoice->client->telefono ?? '900000000' }}</p>
            </td>
            <td width="50%" class="text-right">
                <p>N° Boleta: <strong>#{{ $invoice->id }}</strong></p>
                <p>Fecha: {{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}</p>
                <p>Vendedor: {{ $invoice->user->name }}</p>
                <p>Estado: **{{ $invoice->estado }}**</p>
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
