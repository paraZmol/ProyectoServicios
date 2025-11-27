<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'codigo' => 'SVC001',
                'nombre_servicio' => 'Instalación de Red LAN',
                'descripcion' => 'Servicio de cableado e instalación de red local.',
                'precio' => 125.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'SVC002',
                'nombre_servicio' => 'Mantenimiento de Servidor',
                'descripcion' => 'Revisión y optimización de sistema operativo de servidor.',
                'precio' => 250.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'SVC003',
                'nombre_servicio' => 'Asesoría Remota (1 hora)',
                'descripcion' => 'Soporte técnico y asesoría por hora vía remota.',
                'precio' => 50.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
