<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Client;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tasa de Impuesto (13% basado en el seeder original)
        $ivaRate = 0.13;

        // --- 1. Obtener IDs y Datos Necesarios ---

        // Obtener Clientes (IDs 1-14)
        $clientIds = Client::pluck('id')->toArray();
        if (empty($clientIds)) {
            $this->command->error('No hay clientes. Ejecute ClientSeeder primero.');
            return;
        }

        // Obtener Vendedores (Roles 'admin' y 'trabajador' - IDs 1, 2, 3)
        $vendedorIds = User::whereIn('role', ['admin', 'trabajador'])->pluck('id')->toArray();
        if (empty($vendedorIds)) {
            $this->command->error('No hay vendedores disponibles (admin/trabajador). Ejecute UserSeeder primero.');
            return;
        }

        // Obtener todos los servicios con sus datos (código, nombre, precio, id)
        $services = Service::all(['id', 'codigo', 'nombre_servicio', 'precio'])->toArray();
        if (empty($services)) {
            $this->command->error('No hay servicios disponibles. Ejecute ServiceSeeder primero.');
            return;
            // No se necesitan los servicios para los detalles, por lo que devolvemos un error.
        }

        // Crear un array simple de IDs de servicio para la selección aleatoria
        $serviceIds = array_column($services, 'id');


        // Estados y métodos de pago posibles
        $estados = ['Pagada', 'Pendiente', 'Anulada'];
        $metodosPago = ['Efectivo', 'Tarjeta', 'Transferencia', 'Crédito'];

        $invoicesCount = 0;
        $fechaActual = Carbon::now();

        // --- 2. Generar 15 Boletas ---

        for ($i = 1; $i <= 15; $i++) {
            // Generar fecha en un rango de los últimos 60 días
            $fecha = $fechaActual->copy()->subDays(rand(1, 60))->toDateString();

            // Seleccionar IDs aleatorios
            $clientId = $clientIds[array_rand($clientIds)];
            $userId = $vendedorIds[array_rand($vendedorIds)];
            $estado = $estados[array_rand($estados)];
            $metodoPago = $metodosPago[array_rand($metodosPago)];

            $subtotal = 0;
            $detalles = [];

            // Número aleatorio de servicios (entre 1 y 3)
            $numServicios = rand(1, 3);

            // Seleccionar IDs de servicios únicos para esta boleta
            // Si $numServicios es 1, array_rand devuelve una clave, no un array.
            $selectedServiceKeys = array_rand($serviceIds, $numServicios);

            // Si array_rand devuelve un solo valor, lo convertimos en un array
            $selectedServiceKeys = is_array($selectedServiceKeys) ? $selectedServiceKeys : [$selectedServiceKeys];

            // Obtener los IDs de servicio usando las claves seleccionadas
            $serviciosSeleccionados = [];
            foreach ($selectedServiceKeys as $key) {
                $serviciosSeleccionados[] = $serviceIds[$key];
            }

            // Crear los detalles y calcular el subtotal
            foreach ($serviciosSeleccionados as $serviceId) {
                // Encontrar el objeto servicio completo
                $service = collect($services)->firstWhere('id', $serviceId);

                $cantidad = rand(1, 5); // Cantidad aleatoria de 1 a 5
                $totalLinea = $service['precio'] * $cantidad;
                $subtotal += $totalLinea;

                $detalles[] = [
                    'service_id' => $serviceId,
                    'nombre_servicio' => $service['nombre_servicio'],
                    'cantidad' => $cantidad,
                    'precio_unitario_final' => $service['precio'],
                    'total_linea' => round($totalLinea, 2),
                ];
            }

            // Calculos finales
            $impuesto = $subtotal * $ivaRate;
            $total = $subtotal + $impuesto;

            // Crear la Boleta Principal (Invoice)
            $invoice = Invoice::create([
                'fecha' => $fecha,
                'estado' => $estado,
                'metodo_pago' => $metodoPago,
                'subtotal' => round($subtotal, 2),
                'impuesto' => round($impuesto, 2),
                'total' => round($total, 2),
                'client_id' => $clientId,
                'user_id' => $userId,
            ]);

            // Crear los Detalles de la Boleta (InvoiceDetails)
            foreach ($detalles as $detalle) {
                InvoiceDetail::create(array_merge($detalle, ['invoice_id' => $invoice->id]));
            }

            $invoicesCount++;
        }

        $this->command->info($invoicesCount . ' boletas de prueba (Invoices) creadas exitosamente.');
    }
}
