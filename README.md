<div align="center">
  <img src="public/img/logo_default.png" alt="Logo del Proyecto" width="150"/>
  <h1>ProyectoServicios</h1>
  <p><em>Sistema Profesional de Gestión y Emisión de Boletas de Servicios</em></p>

  [![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)](https://www.php.net/)
  [![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.1-38B2AC?logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
  [![Alpine.js](https://img.shields.io/badge/Alpine.js-3.4-8BC0D0?logo=alpine.js&logoColor=white)](https://alpinejs.dev/)
</div>

---

## 📖 Descripción del Proyecto

**ProyectoServicios** es una plataforma web moderna e integral, diseñada específicamente para la administración de clientes, catálogo de servicios y la **emisión de comprobantes de pago (Boletas de Venta)**.

A diferencia de los sistemas de facturación tradicionales enfocados en comercio minorista (retail) y control de stock, este sistema está **optimizado exclusivamente para el rubro de servicios**, permitiendo una facturación ágil, precios dinámicos y generación instantánea de reportes y tickets térmicos.

## ✨ Características Principales

- **Gestión Ágil de Servicios:** Catálogo con precios base modificables al vuelo durante la venta. Sin módulos innecesarios de inventario.
- **Motor de Comprobantes Multi-formato:** Generación y exportación de boletas en **Web, Ticket Térmico y PDF A4** impulsados por [DomPDF](https://github.com/dompdf/dompdf).
- **Configuración Global Dinámica:** Personalización completa de la instacia (Nombre de la empresa, divisas, logos, favicon y tasas de impuestos) sin tocar código.
- **Cierres de Caja Inmediatos:** Reportes diarios e históricos precisos exportables en formato PDF para el control de la recaudación.
- **Seguridad y Control de Acceso:** Sistema de roles integrados (`Administrador` y `Vendedor/Trabajador`) para delimitar responsabilidades comerciales.

---

## 🛠️ Stack Tecnológico

El sistema ha sido construido sobre una arquitectura robusta, empleando las mejores prácticas del desarrollo web actual:

### Backend
- **Framework:** Laravel 12.x
- **Lenguaje:** PHP `>= 8.2`
- **Base de Datos:** MySQL / MariaDB (Relacional)
- **Generación PDF:** Barryvdh Laravel DomPDF `^3.1`

### Frontend
- **Estilizado:** Tailwind CSS `^3.1` mediante compilación con Vite.
- **Interactividad Reactiva:** Alpine.js `^3.4` para la gestión del estado del lado del cliente (cálculos matemáticos de boletas en tiempo real sin recargar).
- **Iconografía:** FontAwesome Free `^7.1.0`
- **Bundler:** Vite `^7.0`

---

## 🏛️ Metodología y Arquitectura

### 1. Integridad Transaccional (ACID)
La emisión de boletas (Modelo `Invoice`) implementa **Transacciones Estrictas de Base de Datos** (`DB::beginTransaction()` y `DB::rollBack()`). El sistema garantiza que bajo ninguna circunstancia de error (caída de red, error de cálculo) se guarde una boleta incompleta. O se guarda toda la cabecera con sus detalles, o no se guarda nada.

### 2. Modelo de Precios Históricos
Para resolver el problema común de los cambios de tarifa en los servicios, el sistema adopta el patrón de **Instantáneas de Facturación**. El campo `precio_unitario_final` en los detalles de la boleta se congela al momento de la venta, garantizando que el historial contable se mantenga intacto aunque los servicios originales sufran ajustes de precio en el futuro.

### 3. Rutas y Recursos Consolidados (DRY)
Todo el sistema de variables globales para las vistas (como el Logo, Favicon e Información del sistema) está inyectado directamente desde el **`AppServiceProvider`** y renderizado eficientemente a base64 cuando se requiere máxima resiliencia en exportaciones locales de PDFs.

### 4. Seguridad en Capas
Toda la entrada de usuarios pasa por el middleware de seguridad CSRF y validación estricta usando clases **Form Requests** especializadas antes de llegar a cualquier Controlador. El acceso general está defendido por el ecosistema de autenticación de **Laravel Breeze**.

---

## ⚙️ Guía de Instalación y Despliegue

### Prerrequisitos
- PHP >= 8.2
- Composer
- Node.js & npm (>= v18)
- Servidor de base de datos MySQL o MariaDB

### Pasos de Instalación

**1. Clonar el Repositorio**
```bash
git clone https://github.com/paraZmol/ProyectoServicios.git
cd ProyectoServicios
```

**2. Instalar Dependencias del Backend**
```bash
composer install --optimize-autoloader --no-dev
```

**3. Instalar Dependencias del Frontend**
```bash
npm install
npm run build
```

**4. Configuración del Entorno**
Duplica el archivo de configuración de ejemplo y genera la clave de cifrado maestra de la aplicación:
```bash
cp .env.example .env
php artisan key:generate
```
*Abre el archivo `.env` y configura tus credenciales de base de datos (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).*

**5. Migraciones y Semillas de Datos (Seed)**
Este paso creará las tablas necesarias en la base de datos y generará el usuario administrador por defecto:
```bash
# ADVERTENCIA: Este comando borrará los datos existentes si la base ya tuviera tablas.
php artisan migrate:fresh --seed
```

**6. Configuración del Almacenamiento (Importante)**
Crucial para el correcto despliegue local de los logos y ajustes subidos por los administradores de la plataforma.
```bash
php artisan storage:link
```

**7. Iniciar el Servidor de Pruebas Locally**
```bash
php artisan serve
```

---

## 🔒 Credenciales por Defecto

Después de ejecutar la instalación y hacer el *seed*, el sistema generará una cuenta Super Administrador de acceso inmediato:

| Rol | Correo Electrónico | Contraseña |
|-----|-------------------|------------|
| Multi-Admin | `admin@demo.com` | `password` |

*(Se recomienda encarecidamente cambiar esta contraseña inmediatamente después del primer inicio de sesión comercial)*

---

*Desarrollado con altos estándares de calidad y pensado para el escalamiento a futuro.*
