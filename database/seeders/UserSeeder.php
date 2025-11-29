<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        // contraseña comun para todos los seeders
        $password = Hash::make('password');

        DB::table('users')->insert([
            // --- admin ---
            [
                'name' => 'Administrador Demo',
                'email' => 'admin@demo.com',
                'role' => 'admin',
                'password' => $password,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- trabajador ---
            [
                'name' => 'Supervisor Jefe',
                'email' => 'supervisor@demo.com',
                'role' => 'trabajador',
                'password' => $password,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- trabajador ---
            [
                'name' => 'Empleado Activo',
                'email' => 'empleado1@demo.com',
                'role' => 'trabajador',
                'password' => $password,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- usuario ---
            [
                'name' => 'Usuario No Verificado',
                'email' => 'empleado2@demo.com',
                'role' => 'usuario',
                'password' => $password,
                'email_verified_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- usuario ---
            [
                'name' => 'Técnico Soporte',
                'email' => 'tecnico@demo.com',
                'role' => 'usuario',
                'password' => $password,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
