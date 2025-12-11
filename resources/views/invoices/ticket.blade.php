<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            margin: 0 auto;
            padding: 4mm;
            background-color: #fff;
            color: #000;
            max-width: 80mm;
            box-sizing: border-box;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0mm;
            }
            body {
                width: 100%;
                margin: 0;
                padding: 4mm;
            }
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .small-text { font-size: 10px; }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .header-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .header-logo {
            flex: 0 0 25%;
            margin-right: 8px;
        }
        .header-logo img {
            width: 100%;
            height: auto;
            filter: grayscale(100%) contrast(120%);
        }
        .header-info {
            flex: 1;
            text-align: left;
        }

        table { width: 100%; border-collapse: collapse; }
        td, th { vertical-align: top; padding: 2px 0; }
    </style>
</head>
<body onload="window.print()">

    <div class="header-container">
        @if (isset($setting->logo_path) && $setting->logo_path)
        {{-- ruta al logo --}}
            @php
                // asset
                $logoPath = asset('storage/' . $setting->logo_path);
            @endphp
            <div class="header-logo">
                <img
                    src="{{ $logoPath }}"
                    alt="{{ $setting->nombre_empresa ?? 'Logo de la Empresa' }}"
                >
            </div>
        @endif

        <div class="header-info small-text">
            <div class="font-bold uppercase" style="font-size: 12px;">
                {{ $setting->nombre_empresa ?? 'PROYECTO SERVICIOS S.A.' }}
            </div>
            @if(isset($setting->ruc)) RUC: {{ $setting->ruc }} <br> @endif
            Dir: {{ Str::limit($setting->direccion ?? 'Dirección Principal', 35) }} <br>
            Email: {{ Str::limit($setting->correo_electronico ?? $setting->email, 35) }}<br>
            Telf: {{ $setting->telefono ?? '-' }}
        </div>
    </div>

    <div class="divider"></div>

    <div class="text-center">
        <strong style="font-size: 12px;">BOLETA DE VENTA ELECTRÓNICA</strong><br>
        <span class="font-bold" style="font-size: 13px;">
            {{ $invoice->serie ?? 'B001' }}-{{ str_pad($invoice->correlativo ?? $invoice->id, 6, '0', STR_PAD_LEFT) }}
        </span><br>
    </div>

    <div class="text-left small-text" style="margin-top: 5px;">
        <strong>Fecha emisión:</strong> {{ \Carbon\Carbon::parse($invoice->fecha)->format('d/m/Y') }}<br>
        <strong>Vendedor:</strong> {{ Str::limit($invoice->user->name, 25) }}<br>
        <strong>Estado:</strong> {{ $invoice->estado }}<br>
        <strong>Condición de pago:</strong> {{ Str::limit($invoice->metodo_pago, 25) }}
    </div>

    <div class="divider"></div>

    <div class="small-text">
        <strong>Cliente:</strong> {{ Str::limit($invoice->client->nombre, 35) }}<br>
        @if($invoice->client->dni)
            <strong>{{ $invoice->client->tipo_documento }}:</strong> {{ $invoice->client->documento_oculto }}<br>
        @else
            <strong>Doc:</strong> - <br>
        @endif
        @if($invoice->client->direccion)
            <strong>Email:</strong> {{ Str::limit($invoice->client->email, 40) }}<br>
        @endif
        @if($invoice->client->direccion)
            <strong>Dir:</strong> {{ Str::limit($invoice->client->direccion, 40) }}<br>
        @endif
    </div>

    <div class="divider"></div>

    {{-- cambio de tabla --}}
    <table class="small-text" style="table-layout: fixed; width: 100%;">
        <thead>
            <tr>
                {{-- nombre de servicio --}}
                <th class="text-left" style="width: auto;">DESCRIPCION</th>

                {{-- canttida --}}
                <th class="text-right" style="width: 40px; padding-right: 20px;">CANT</th>
                {{-- === CAMBIO FIN === --}}

                {{-- total linea --}}
                <th class="text-right" style="width: 55px;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->details as $item)
            <tr>
                {{-- cambio a 2 lineas y puntos --}}
                <td class="font-bold text-left" style="padding-top: 4px;">
                    <div style="
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: normal;
                        line-height: 1.2;
                    ">
                        {{ $item->nombre_servicio }}
                    </div>
                </td>
                {{-- vacio a completar--}}
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-left" style="color: #444; padding-left: 5px;">
                    (P.U: {{ number_format($item->precio_unitario_final, 2) }})
                </td>

                <td class="text-right" style="padding-right: 20px;">{{ $item->cantidad }}</td>

                <td class="text-right">{{ number_format($item->total_linea, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="small-text" style="display: flex; flex-direction: column; align-items: flex-end;">
        <div>
            <span style="margin-right: 10px;">Subtotal:</span>
            <span>{{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->subtotal, 2) }}</span>
        </div>
        <div>
            <span style="margin-right: 10px;">Impuesto ({{ number_format($setting->iva_porcentaje ?? '18', 0) }}%):</span>
            <span>{{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->impuesto, 2) }}</span>
        </div>

        <div style="border-top: 1px solid #000; margin-top: 4px; padding-top: 4px; font-size: 13px; font-weight: bold;">
            <span style="margin-right: 10px;">TOTAL A PAGAR:</span>
            <span>{{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->total, 2) }}</span>
        </div>
    </div>

    {{-- slado en caso de haber pendientes --}}
    @if($invoice->estado == 'Pendiente')
        <div style="width: 100%; border-top: 1px dashed #000; margin-top: 5px; padding-top: 5px; text-align: right;">
            <div>
                <span style="margin-right: 10px;">A Cuenta:</span>
                <span>{{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->monto_pagado, 2) }}</span>
            </div>
            <div style="font-weight: bold;">
                <span style="margin-right: 10px;">Saldo Pendiente:</span>
                <span>{{ $setting->simbolo_moneda ?? 'S/' }} {{ number_format($invoice->total - $invoice->monto_pagado, 2) }}</span>
            </div>
        </div>
    @endif

    <div class="divider"></div>

    <div class="text-center small-text" style="margin-top: 5px;">
        <p style="margin-bottom: 5px;">¡GRACIAS POR SU PREFERENCIA!</p>
        <p style="margin-bottom: 5px;">Conserve este ticket para cualquier reclamo.</p>
    </div>

</body>
</html>
