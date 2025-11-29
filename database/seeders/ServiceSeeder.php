<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('services')->insert([
            [
                'codigo' => 'SVC001',
                'nombre_servicio' => 'Instalación de Red LAN',
                'descripcion' => 'Servicio de cableado e instalación de red local, incluyendo configuración básica.',
                'precio' => 125.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC002',
                'nombre_servicio' => 'Mantenimiento de Servidor',
                'descripcion' => 'Revisión, optimización y actualización del sistema operativo y software de servidor.',
                'precio' => 250.50,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC003',
                'nombre_servicio' => 'Asesoría Remota (1 hora)',
                'descripcion' => 'Soporte técnico y asesoría por hora vía remota, incluye diagnóstico inicial.',
                'precio' => 50.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC004',
                'nombre_servicio' => 'Desarrollo Web (Página Estática)',
                'descripcion' => 'Diseño y desarrollo de una página web estática informativa (hasta 5 secciones).',
                'precio' => 500.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC005',
                'nombre_servicio' => 'Instalación y Configuración de Software',
                'descripcion' => 'Instalación, licenciamiento y configuración inicial de software especializado en un equipo.',
                'precio' => 75.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC006',
                'nombre_servicio' => 'Recuperación de Datos',
                'descripcion' => 'Intento de recuperación de datos borrados o perdidos de un disco duro o medio de almacenamiento dañado.',
                'precio' => 350.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC007',
                'nombre_servicio' => 'Migración a la Nube (Básico)',
                'descripcion' => 'Migración de correo electrónico o archivos de servidor local a una plataforma de nube.',
                'precio' => 450.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC008',
                'nombre_servicio' => 'Limpieza y Optimización de PC/Laptop',
                'descripcion' => 'Mantenimiento preventivo, limpieza física interna y optimización del sistema operativo.',
                'precio' => 85.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC009',
                'nombre_servicio' => 'Auditoría de Seguridad (Red)',
                'descripcion' => 'Análisis de vulnerabilidades de la red local y dispositivos conectados.',
                'precio' => 600.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC010',
                'nombre_servicio' => 'Configuración de Firewall/VPN',
                'descripcion' => 'Instalación y configuración de reglas de firewall o servicio de red privada virtual (VPN).',
                'precio' => 175.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC011',
                'nombre_servicio' => 'Capacitación de Usuario (2 horas)',
                'descripcion' => 'Sesión de capacitación personalizada sobre el uso de nuevo software o sistemas.',
                'precio' => 100.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC012',
                'nombre_servicio' => 'Respaldo de Información (Backup)',
                'descripcion' => 'Configuración de un sistema de respaldo automático de información en medio externo o nube.',
                'precio' => 90.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC013',
                'nombre_servicio' => 'Soporte Técnico Presencial (1 hora)',
                'descripcion' => 'Diagnóstico y resolución de problemas técnicos en sitio por una hora.',
                'precio' => 65.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC014',
                'nombre_servicio' => 'Configuración de Correo Electrónico',
                'descripcion' => 'Configuración de cuentas de correo electrónico corporativo en múltiples dispositivos.',
                'precio' => 40.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'codigo' => 'SVC015',
                'nombre_servicio' => 'Desarrollo de E-commerce (Básico)',
                'descripcion' => 'Implementación inicial de una tienda en línea con catálogo de productos y pasarela de pago.',
                'precio' => 999.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}