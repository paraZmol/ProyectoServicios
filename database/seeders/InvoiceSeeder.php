<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Client;
use App\Models\User;
use App\Models\Service;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ids necesarios
        $cliente = Client::first();
        $vendedor = User::first();
        $servicioInstalacion = Service::where('codigo', 'SVC001')->first();
        $servicioMantenimiento = Service::where('codigo', 'SVC002')->first();

        // en caso de no haber cliente
        if (!$cliente) {
            $cliente = Client::create(['nombre' => 'Cliente Seeder', 'telefono' => '900000000', 'dni' => '12345678', 'email' => 'seeder@test.com', 'direccion' => 'Calle Falsa 123', 'estado' => 'activo']);
        }
        if (!$vendedor) {
            $vendedor = User::factory()->create();
        }

        // en caso de no haber vendedor
        if (!$servicioInstalacion) {
             $servicioInstalacion = Service::create(['codigo' => 'SVC001', 'nombre_servicio' => 'Instalación de Red LAN', 'precio' => 125.00, 'descripcion' => 'Instalación básica', 'is_active' => true]);
        }
        if (!$servicioMantenimiento) {
             $servicioMantenimiento = Service::create(['codigo' => 'SVC002', 'nombre_servicio' => 'Mantenimiento de Servidor', 'precio' => 250.50, 'descripcion' => 'Mantenimiento preventivo', 'is_active' => true]);
        }

        // calculos
        $ivaRate = 0.13;
        $subtotal = (125.00 * 2) + (250.50 * 1);
        $impuesto = $subtotal * $ivaRate;
        $total = $subtotal + $impuesto;

        // crear la boleta
        $invoice = Invoice::create([
            'fecha' => now()->toDateString(),
            'estado' => 'Pagada',
            'metodo_pago' => 'Efectivo',
            'subtotal' => round($subtotal, 2),
            'impuesto' => round($impuesto, 2),
            'total' => round($total, 2),
            'client_id' => $cliente->id,
            'user_id' => $vendedor->id,
        ]);

        // detalles de la boleta

        // detalle 1 - instalacion de lan
        InvoiceDetail::create([
            'invoice_id' => $invoice->id,
            'service_id' => $servicioInstalacion->id,
            'nombre_servicio' => $servicioInstalacion->nombre_servicio,
            'cantidad' => 2,
            'precio_unitario_final' => $servicioInstalacion->precio,
            'total_linea' => round(125.00 * 2, 2),
        ]);

        // detalle 2 - mantenimiento de servidor
        InvoiceDetail::create([
            'invoice_id' => $invoice->id,
            'service_id' => $servicioMantenimiento->id,
            'nombre_servicio' => $servicioMantenimiento->nombre_servicio,
            'cantidad' => 1,
            'precio_unitario_final' => $servicioMantenimiento->precio,
            'total_linea' => round(250.50 * 1, 2),
        ]);

        $this->command->info('Boleta de prueba creada exitosamente. Total: ' . round($total, 2));
    }
}