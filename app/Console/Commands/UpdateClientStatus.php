<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateClientStatus extends Command
{
    protected $signature = 'clients:update-status';
    protected $description = 'Pasa a inactivo a los clientes sin compras en el último año';

    public function handle()
    {
        // fecha limite de un año
        $fechaLimite = Carbon::now()->subYear();

        // busqueda de clientes activos para que sean inactivos
        $afectados = Client::where('estado', 'activo')
            ->whereHas('invoices') // que haya historial
            ->whereDoesntHave('invoices', function ($q) use ($fechaLimite) {
                // que no haya boletas recientes
                $q->where('fecha', '>=', $fechaLimite);
            })
            ->update(['estado' => 'inactivo']);

        $this->info("Se han desactivado {$afectados} clientes por inactividad.");
        Log::info("Limpieza de clientes: {$afectados} pasaron a inactivos.");
    }
}
