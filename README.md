<div align="center">
  <img src="public/img/logo_default.png" alt="Logo del Proyecto" width="150"/>
  <h1>ProyectoServicios v3.0.0</h1>
  <p><em>Sistema Profesional de Gestión Operativa y Emisión de Boletas de Servicios</em></p>

  [![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)](https://www.php.net/)
  [![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
  [![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.1-38B2AC?logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
  [![Alpine.js](https://img.shields.io/badge/Alpine.js-3.4-8BC0D0?logo=alpine.js&logoColor=white)](https://alpinejs.dev/)
</div>

---

## 📖 Descripción del Proyecto

**ProyectoServicios** es una plataforma web moderna, resiliente e integral, concebida específicamente para la administración de clientes, catálogo de servicios y la **emisión de comprobantes de pago (Boletas de Venta)**.

A diferencia de los sistemas de facturación tradicionales enfocados en comercio minorista (retail) y control de stock físico, este sistema está **optimizado exclusivamente para el rubro de servicios corporativos**, permitiendo una facturación ágil, estatus de pagos pendientes, precios alterables al vuelo y generación instantánea de tickets térmicos. Todo esto envuelto en una interfaz de usuario completamente responsiva (Mobile-first).

---

## ✨ Características y Funcionalidades Clave

### 1. Panel Cero-Pérdida (Papeleras de Reciclaje / SoftDeletes)
Implementación integral de `SoftDeletes` de Laravel para Clientes, Servicios, Boletas y Usuarios. Ningún registro se destruye por error; todo pasa a una sala de Papeleras exclusivas del Administrador donde pueden ser restauradas o eliminadas de manera destructiva (Force Delete) bajo confirmación de un modal de advertencia.

### 2. Diseño 100% Responsivo y Mobile-First
La interfaz construida sobre Tailwind CSS se adapta dinámicamente. Desde el panel principal hasta el Punto de Venta (POS) y los reportes, todo está diseñado para operarse desde computadoras de escritorio, tablets o smartphones de los técnicos en campo.

### 3. Motor de Comprobantes Multi-formato
Generación y exportación de boletas en **Web, Ticket Térmico y PDF A4** impulsados por [DomPDF](https://github.com/dompdf/dompdf). El sistema es plenamente compatible con impresión en Windows y renderiza logos de forma nativa e irrompible mediante Base64.

### 4. Gestión Ágil de Servicios (Sin Stock)
Catálogo con precios base modificables en el carrito al momento de cobrar. Elimina la fricción de los sistemas tradicionales al no depender de módulos innecesarios de inventario o ingreso de mercancía.

### 5. Estados de Cobranza (Crédito/Pendientes)
Soporte nativo para tres estados comerciales:
- **Pagada:** Liquidación total al momento.
- **Pendiente:** Registra adelantos ("A cuenta"), calcula el saldo restante e imprime notas personalizadas del acuerdo.
- **Anulada:** Mantiene registro histórico transparente y cálculos de cuadre pero anula los montos recaudados.

### 6. Configuración Global Dinámica
Personalización completa de la instancia corporativa (Nombre de la empresa, divisas, logos, favicon y tasas de impuestos) sin tocar código, centralizada a través del proveedor del sistema.

### 7. Cierres de Caja y Cuadres
Reportes diarios e históricos precisos exportables en formato PDF. Segrega de manera automatizada lo que fue liquidado al contado versus los "adelantos" de recibos en estado Pendiente protegiendo la recaudación de cada vendedor por fecha.

---

## 📋 Requisitos del Sistema (Ingeniería)

### Requerimientos Funcionales (RF)
- **RF1. Punto de Venta Dinámico:** El sistema debe permitir alterar el precio de los servicios temporalmente durante la venta, sumando cantidades automáticas sin alterar el registro maestro del catálogo.
- **RF2. Prevención de Borrado:** Eliminación lógica de facturas y clientes hacia una Papelera de reciclaje accesible por un Navbar Dropdown exclusivo para administradores.
- **RF3. Liquidación Transaccional:** Debe soportar ingresos tipo `A Cuenta` registrando una factura como "Pendiente" y auto-calculando el "Saldo deudor".
- **RF4. Impresión Unificada:** Todas las salidas gráficas para clientes (Boletas web, Ticket Impresora) consumirán una única fuente de verdad para el Logo e Icono.

### Requerimientos No Funcionales (RNF)
- **RNF1. Integridad de Base de Datos (ACID):** El guardado o actualización de una boleta con sus filas (detalles) debe realizarse bajo un motor InnoDb relacional estricto con bloqueos por transacción pre-configurados.
- **RNF2. Reactividad UI/UX:** Todo cálculo de importes, sub-totales e impuestos durante la venta debe ocurrir instantáneamente vía JavaScript local (Alpine.js) evitando recargas PUG-like.
- **RNF3. Adaptabilidad (Responsive):** La plataforma se redimensionará para resoluciones de celular y PC sin degradar la visualización de tablas.

---

## 🎭 Casos de Uso Críticos (UML Operativo)

### CU01: Facturación con Adelanto Monetario (Deuda)
1. El cajero abre el carrito, escoge el cliente y el `Servicio de Reparación`.
2. Como es un servicio que tardará días, cambia el estado de la cabecera a `Pendiente`.
3. Inmediatamente el sistema despliega el campo `Monto Pagado` y el área de `Notas` temporales.
4. El cajero ingresa el adelanto (Ej: 10 dólares de 50 totales).
5. El sistema imprime el Ticket al cliente estipulando que su Saldo Pendiente por cobrar es de 40 dólares acompañando la nota de promesa del negocio.

### CU02: Arqueo y Corte de Caja
1. Al final del turno, el Trabajador ejecuta la exportación del "Reporte Diario" de su propio ID de Vendedor.
2. El sistema compila todas las boletas del lapso del día agrupando: Cajas de Pagadas directas + Dinero en mano de las parciales.
3. El documento lista y cruza la contabilidad dejando listos los faltantes (Por Cobrar mañanas).

### CU03: Recuperación de Desastres de Escritorio
1. Un Trabajador por error humano o prisa elimina una Boleta completada desde el Dashboard.
2. La factura desaparece del flujo del vendedor pero la recaudación original queda temporalmente bloqueada.
3. El Administrador ingresa al `<nav>` superior Papeleras -> Boletas y la restaura instantáneamente reactivando la finanza o la depura definitivamente confirmando en la ventana de advertencia (Force Delete).

---

## 🛡️ Arquitectura y Stack Tecnológico

El sistema requiere de una arquitectura estricta para garantizar seguridad:

- **Motor de Base de Datos Único:** Obligatoriamente dependiente de **MySQL** (o compatibles estrictos como MariaDB).
- **Backend:** Laravel 12.x sobre PHP >= 8.2 empleando **Transacciones DB** (`DB::beginTransaction()`). Bajo ningún error de red se guardará un comprobante "incompleto" que corrompa la caja. 
- **Precios Históricos Inmutables:** Los precios en los tickets son instantáneas (snapshots). Si los directivos cambian el catálogo mañana, los PDF de las boletas pasadas mantendrán su legalidad de precios intocable.
- **Frontend Reactivo:** Impulsado por Tailwind CSS y el puente declarativo Alpine.js.

---

## 👥 Roles y Permisos

Para mantener jerarquía, se manejan tres roles preconfigurados desde los Seeders (`RolePermissionSeeder` / `UserSeeder`):

| Rol Interno | Nivel de Acceso y Atribuciones |
|-------------|--------------------------------|
| `admin` | **Administrador General:** Tiene acceso total. Único con pase autorizado a las **Papeleras de Reciclaje** para recuperar o depurar (`Force Delete`). Modifica IVA, logos, anula boletas, y audita historiales de todos. |
| `trabajador` | **Personal Operativo/Cajero:** Puede emitir boletas, buscar clientes, cambiar el estatus de pendiente a pagado e imprimir cierres diarios de **su propia recuadación.** |
| `usuario` | **Técnico o Restringido:** Nivel básico del sistema para lectura limitante o cuentas base suspendidas. |

---

## 🔄 Historial de Versiones (Changelog)

### **v3.0.0 (Gran Actualización Actual)**
- **Feature [Core]:** Sistema completado con Papeleras de Reciclaje modulares para todas las grandes entidades (Clientes, Servicios, Boletas, Usuarios). Restauraciones en 1-click.
- **Feature:** Implementados los *"Adelantos"*, *"Saldos por Cobrar"* y notas de estado en las vistas.
- **UX/UI:** Migración estricta al estándar Mobile-First responsivo para uso en tables y móviles.
- **Fix (Critical):** Unificada la inyección universal gráfica. Todos los exportables en entorno Windows e impresoras térmicas ahora convierten los logotipos a Base64 impidiendo imágenes rotas por path de Storage de Laravel.

### **v2.x.x**
- Mejoras de UI, agregado transaccional en el carrito de compras y control lógico de inventario sin stock.

### **v1.0.0**
- Núcleo básico de autenticación Breeze y cruds monolíticos estáticos.

---

## ⚙️ Guía de Instalación y Despliegue (Producción)

### Prerrequisitos de Servidor
- Servidor Web (Apache/Nginx/Litespeed)
- PHP >= 8.2 (Con extensión GD para logos)
- **MySQL >= 8.0**
- Node.js & npm (>= v18)

### Instrucciones

**1. Despliegue del Repositorio**
```bash
git clone https://github.com/paraZmol/ProyectoServicios.git
cd ProyectoServicios
```

**2. Instalación de Motor (Backend/Frontend)**
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

**3. Configuración y Claves**
Duplicar el `.env.example`, nombrarlo `.env`. Conecta de manera mandatoria con MySQL.
```bash
cp .env.example .env
php artisan key:generate
```

**4. Migraciones de Arquitectura MySQL**
Estructurará el catálogo relacional y sembrará los roles principales en la DB limpia.
```bash
php artisan migrate:fresh --seed
```

**5. Enlace Pila Multimedia (CRÍTICO)**
Comando indispensable para liberar la ruta absoluta en la que AppServiceProvider procesa los logotipos para los PDFs y tickets:
```bash
php artisan storage:link
```

El servidor está listo. Apunta tu DocumentRoot a la carpeta `/public` o lanza php artisan serve en caso estés probando en escritorio local.

---
*Respaldado por metodologías sólidas para software en Puntos de Venta (POS).*
