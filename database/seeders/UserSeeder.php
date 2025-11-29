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
                'password' => $password,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- supervisor ---
            [
                'name' => 'Supervisor Jefe',
                'email' => 'supervisor@demo.com',
                'password' => $password,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- emepleado activo ---
            [
                'name' => 'Empleado Activo',
                'email' => 'empleado1@demo.com',
                'password' => $password,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- emepleado sin verificar ---
            [
                'name' => 'Usuario No Verificado',
                'email' => 'empleado2@demo.com',
                'password' => $password,
                'email_verified_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- tecnico ---
            [
                'name' => 'Técnico Soporte',
                'email' => 'tecnico@demo.com',
                'password' => $password,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}