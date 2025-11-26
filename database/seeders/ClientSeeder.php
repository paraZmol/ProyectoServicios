<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clients')->insert([
            [
                'nombre' => 'Juan Pérez (Activo)',
                'telefono' => '998877665',
                'email' => 'juan.perez@test.com',
                'direccion' => 'Calle Falsa 123',
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Maria López (Inactivo)',
                'telefono' => '912345678',
                'email' => 'maria.lopez@test.com',
                'direccion' => 'Av. Siempre Viva 45',
                'estado' => 'inactivo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Cliente sin Email',
                'telefono' => '900000000',
                'email' => null,
                'direccion' => 'Sin dirección',
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
