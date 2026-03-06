<div align="center">
  <img src="public/img/logo_default.png" alt="Logo del Proyecto" width="150"/>
  <h1>ProyectoServicios v1.1.0</h1>
  <p><em>Sistema Profesional de Gestión Operativa y Emisión de Boletas de Servicios</em></p>

  [![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)](https://www.php.net/)
  [![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
  [![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.1-38B2AC?logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
  [![Alpine.js](https://img.shields.io/badge/Alpine.js-3.4-8BC0D0?logo=alpine.js&logoColor=white)](https://alpinejs.dev/)
</div>

---

## 📖 Descripción del Proyecto

**ProyectoServicios** es una plataforma web moderna e integral, concebida específicamente para la administración de clientes, catálogo de servicios y la **emisión de comprobantes de pago (Boletas de Venta)**.

A diferencia de los sistemas de facturación tradicionales enfocados en comercio minorista (retail) y control de stock físico, este sistema está **optimizado exclusivamente para el rubro de servicios corporativos**, permitiendo una facturación ágil, estatus de pagos pendientes, precios alterables al vuelo y generación instantánea de tickets térmicos.

## ✨ Características y Funcionalidades Clave

### 1. Motor de Comprobantes Multi-formato
Generación y exportación de boletas en **Web, Ticket Térmico y PDF A4** impulsados por [DomPDF](https://github.com/dompdf/dompdf). El sistema es plenamente compatible con impresión en Windows y renderiza logos de forma nativa e irrompible mediante Base64.
### 2. Gestión Ágil de Servicios (Sin Stock)
Catálogo con precios base modificables al momento de cobrar. Elimina la fricción de los sistemas tradicionales al no depender de módulos innecesarios de inventario.
### 3. Estados de Cobranza (Crédito/Pendientes)
Soporte nativo para tres estados comerciales:
- **Pagada:** Liquidación total al momento.
- **Pendiente:** Registra adelantos ("A cuenta"), calcula el saldo restante e imprime notas personalizadas del acuerdo.
- **Anulada:** Mantiene registro histórico transparente y cálculos de cuadre pero anula los montos recaudados.
### 4. Configuración Global Dinámica
Personalización completa de la instancia corporativa (Nombre de la empresa, divisas, logos, favicon y tasas de impuestos) sin tocar código, todo desde un panel administrativo.
### 5. Cierres de Caja Inmediatos
Reportes diarios e históricos precisos exportables en formato PDF. Segrega de manera automatizada lo que fue liquidado al contado versus los "adelantos" de recibos en estado Pendiente.

---

## 🏛️ Metodología de Trabajo y Operación (Modus Operandi)

El uso del sistema sigue un flujo de trabajo lineal diseñado para mantener la integridad contable:

1. **Catálogos Base:** El administrador puebla primero los servicios genéricos y ajusta los parámetros de la empresa en *Configuración*.
2. **Registro de Cliente:** Al solicitar un servicio, el trabajador registra al cliente en la base de datos (DNI/RUC) o lo selecciona del autocompletado si es recurrente.
3. **Punto de Venta:** En el panel de boletas, se agregan los servicios prestados. El *Trabajador* tiene libertad de sobreescribir el precio base pactado con el cliente sin afectar el catálogo maestro.
4. **Acuerdo de Pago:** Si el servicio tomará días, la boleta se emite como **"Pendiente"** y se anota el abono inicial. El cliente recibe su ticket con el *"Saldo Deudor"*.
5. **Cierre de Ciclo:** Al final del día, el cajero imprime su **Reporte Diario** que hace match contra el cajón físico, separando el dinero recaudado de servicios finalizados y el dinero de los abonos adelantados.

---

## 🛡️ Arquitectura y Stack Tecnológico

El sistema requiere de una arquitectura estricta para garantizar seguridad:

- **Motor de Base de Datos Único:** Obligatoriamente dependiente de **MySQL** (o compatibles estrictos como MariaDB).
- **Backend:** Laravel 12.x sobre PHP >= 8.2 empleando **Transacciones DB** (`DB::beginTransaction()`). Bajo ningún error de red se guardará un comprobante "incompleto" que corrompa la caja. 
- **Precios Históricos Inmutables:** Los precios en los tickets son instantáneas (snapshots). Si los directivos cambian el catálogo mañana, los PDF de las boletas pasadas mantendrán su legalidad de precios intocable.
- **Frontend Reactivo:** Impulsado por Tailwind CSS y el puente declarativo Alpine.js para auto-cálculos de IVA e IGV sin recargar páginas.

---

## 👥 Roles y Permisos

Para mantener jerarquía, se manejan tres roles preconfigurados desde los Seeders (`RolePermissionSeeder` / `UserSeeder`):

| Rol Interno | Nivel de Acceso y Modus Operandi |
|-------------|---------------------------------|
| `admin` | **Administrador General:** Tiene acceso total. Puede modificar ajustes globales de IVA, divisas, logos corporativos, anular boletas, y auditar historiales. |
| `trabajador` | **Personal Operativo/Cajero:** Puede emitir boletas, buscar información comercial, cambiar estatus de pendiente a pagado e imprimir cierres diarios de su propia caja. |
| `usuario` | **Técnico o Invitado:** Nivel básico del sistema para lectura limitante o cuentas suspendidas. |

---

## 🔄 Changelog y Actualizaciones Recientes

### **v1.1.0 (Actual)**
- **Feature:** Implementados los *"Adelantos"* y *"Saldos por Cobrar"* en los tickets de boletas pendientes con campos de notas opcionales.
- **Fix (Critical):** Unificada la inyección universal gráfica. Todos los exportables en entorno Windows e impresoras térmicas ahora convierten y leen los logotipos empresariales de forma local nativa utilizando variables pre-compiladas en Base64 desde el proveedor global.

### **v1.0.0**
- Lanzamiento inicial del módulo de facturación, módulos cliente, y exportadores DomPDF.

---

## ⚙️ Guía de Instalación y Despliegue (Producción)

### Prerrequisitos de Servidor
- Servidor Web (Apache/Nginx)
- PHP >= 8.2
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
Deberás duplicar el `.env.example`, nombrarlo `.env`. Posteriormente debes definir `DB_CONNECTION=mysql` y apuntar a tu propia base de datos, con tu contraseña.
```bash
cp .env.example .env
php artisan key:generate
```

**4. Migraciones de Arquitectura MySQL**
Estructurará el catálogo relacional y los roles principales.
```bash
php artisan migrate:fresh --seed
```

**5. Enlace Pila Multimedia (CRÍTICO)**
Comando obligatorio para que el sistema de impresión logre leer los logotipos y firmas subidas al servidor:
```bash
php artisan storage:link
```

El servidor está listo para ser servido por tu hosting o entorno de apache/nginx. En caso de emulación local, ejecuta `php artisan serve`.

---
*Diseñado bajo estándares rigurosos de operaciones empresariales.*
