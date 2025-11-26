<?php
// database/seeders/SettingSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Solo debe haber un registro (ID 1)
        DB::table('settings')->insert([
            'nombre_empresa' => 'PROYECTO SERVICIOS S.A.',
            'telefono' => '+51 987 654 321',
            'email' => 'contacto@servicios.com',
            'simbolo_moneda' => 'S/',
            'iva_porcentaje' => 13.00,
            'direccion' => 'Av. Central 123, Lima',
            'ciudad' => 'Lima',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
