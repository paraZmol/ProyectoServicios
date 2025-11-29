<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('clients')->insert([
            [
                'nombre' => 'Juan Pérez',
                'telefono' => '998877665',
                'dni' => '20202020',
                'email' => 'juan.perez@test.com',
                'direccion' => 'Calle Falsa 123, Urb. Los Álamos',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Maria López',
                'telefono' => '912345678',
                'dni' => null,
                'email' => 'maria.lopez@test.com',
                'direccion' => 'Av. Siempre Viva 45, Edif. Apto 101',
                'estado' => 'inactivo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Cliente sin Email',
                'telefono' => '900000000',
                'dni' => '11111111',
                'email' => null,
                'direccion' => 'Sin dirección registrada',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Carlos Ramírez',
                'telefono' => '923456789',
                'dni' => '33333333',
                'email' => 'carlos.ramirez@example.net',
                'direccion' => 'Jr. La Merced 789, Santa Rosa',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Ana Torres',
                'telefono' => '954321098',
                'dni' => '44444444',
                'email' => 'ana.torres@empresa.com',
                'direccion' => 'Paseo de la República 500, Ofic. 10',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Pedro Soto',
                'telefono' => '967890123',
                'dni' => null,
                'email' => 'pedro.soto@otro.org',
                'direccion' => 'Carretera Central Km 5.5',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Laura Mena',
                'telefono' => '934567890',
                'dni' => '55555555',
                'email' => 'laura.mena@servicios.pe',
                'direccion' => 'Mz C Lote 15, Los Olivos',
                'estado' => 'inactivo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Gerardo Castro',
                'telefono' => '987654321',
                'dni' => '66666666',
                'email' => 'gerardo.castro@mail.co',
                'direccion' => 'Av. El Sol 100, Piso 3',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Natalia Ríos',
                'telefono' => '910293847',
                'dni' => '77777777',
                'email' => 'natalia.rios@gmail.com',
                'direccion' => 'Calle San Martín 300, Dpto 2',
                'estado' => 'inactivo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Roberto Vidal',
                'telefono' => '901928374',
                'dni' => '88888888',
                'email' => 'roberto.vidal@hotmail.com',
                'direccion' => 'Jr. Los Diamantes 50, Urb. La Joya',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Susana Reyes',
                'telefono' => '976543210',
                'dni' => '99999999',
                'email' => 'susana.reyes@corporativo.com',
                'direccion' => 'Alameda Pardo 200, Sector B',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Javier Morales',
                'telefono' => '945362718',
                'dni' => '10101010',
                'email' => 'javier.morales@web.es',
                'direccion' => 'Pasaje Las Flores s/n',
                'estado' => 'inactivo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Elena Vásquez',
                'telefono' => '987098709',
                'dni' => '12121212',
                'email' => 'elena.vasquez@outlook.com',
                'direccion' => 'Calle Los Ángeles 405',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Fernando Salas',
                'telefono' => '955443322',
                'dni' => '13131313',
                'email' => 'fernando.salas@datos.net',
                'direccion' => 'Av. La Marina 800',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}