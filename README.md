📝 ProyectoServicios: Sistema de Gestión y Emisión de Boletas de ServicioEste proyecto es una aplicación web construida con el framework Laravel (PHP) para la gestión interna de clientes, servicios y la emisión de comprobantes de venta (Boletas). El sistema fue desarrollado desde cero para reemplazar un sistema de facturación tradicional por uno optimizado para la venta de servicios y la gestión de roles.✨ Características PrincipalesGestión de Servicios (NO Productos): Módulo optimizado para registrar servicios con código y precio base, sin necesidad de manejar inventario o stock.Emisión de Boletas Dinámicas: Creación de boletas con cálculo automático de impuestos (IVA/IGV) utilizando Alpine.js para la lógica de frontend. Permite la modificación del precio unitario en el momento de la venta.Seguridad y Roles: Control de acceso básico para la gestión de usuarios (Administrador / Vendedor).Configuración Global: Módulo para establecer el nombre de la empresa, tasa de IVA, símbolo monetario y dirección para la impresión de boletas.Arquitectura Sólida: Uso de Form Requests y Transacciones de Base de Datos para asegurar la integridad de los datos en cada boleta.⚙️ Instalación y ConfiguraciónSigue estos pasos para levantar el proyecto en tu entorno local.1. Clonar el RepositorioAsegúrate de estar en el directorio donde quieres alojar tu proyecto.git clone [https://github.com/paraZmol/ProyectoServicios.git](https://github.com/paraZmol/ProyectoServicios.git)
cd ProyectoServicios/servis

2. Configurar el EntornoInstala las dependencias de Composer (Backend) y Node (Frontend/CSS).# 1. Instalar dependencias de PHP
composer install

# 2. Copiar el archivo de entorno y generar la clave de aplicación
cp .env.example .env
php artisan key:generate

# 3. Instalar dependencias de JavaScript/Node
npm install

# 4. Compilar los estilos CSS y JavaScript
npm run dev

3. Configuración de la Base de DatosAsegúrate de que tu archivo .env apunte a una base de datos MySQL (o similar) y luego ejecuta las migraciones y el seed de datos.# Crear la base de datos (si no existe) y configurar DB_DATABASE en .env
# Esto limpiará la base de datos, creará todas las tablas y añadirá datos iniciales.
php artisan migrate:fresh --seed

4. Enlace Simbólico (Storage)Necesario para que el logo de la empresa (módulo de Configuración) se pueda mostrar correctamente.php artisan storage:link

5. Iniciar el Servidorphp artisan serve

El sistema estará accesible en http://127.0.0.1:8000 o http://localhost:8000.🔒 Credenciales de AccesoEl comando migrate:fresh --seed crea un usuario administrador por defecto:|| Rol | Email | Contraseña || Administrador | admin@demo.com | password |📐 Arquitectura de la Base de DatosLa aplicación utiliza un modelo relacional estricto centrado en la integridad de las ventas (Boletas).| Entidad | Propósito | Relaciones Clave || users | Vendedores y Administradores. Contiene el campo role. | 1:N con invoices || clients | Clientes del servicio. | 1:N con invoices || services | Catálogo de servicios ofrecidos. | 1:N con invoice_details || settings | Configuración global (IVA, Moneda, Nombre de la Empresa). Es un Singleton. | Ninguna || invoices | Cabecera de la Boleta (Fecha, Cliente, Totales). | 1:N con invoice_details || invoice_details | Ítems de la Boleta. Guarda el precio final y el nombre del servicio para la integridad histórica. | N:1 con invoices, N:1 con services |📝 Puntos Clave para el Mantenimiento1. Nomenclatura del CódigoInternamente, los modelos y las tablas (invoices, services) usan el plural en inglés, pero en la interfaz de usuario (vistas) se utiliza la nomenclatura comercial "Boletas" y "Servicios".2. Gestión de RolesEl sistema maneja dos roles primarios que se encuentran en el campo role de la tabla users:admin: (Rol principal, mapeado desde la vista si se requiere).vendedor: (Rol operativo, mapeado desde la vista como Trabajador).El validador (UserStoreRequest.php) convierte el rol 'Trabajador' de la vista al rol 'vendedor' para la base de datos.3. Lógica de Boletas (Transacciones)La creación de una boleta está envuelta en una transacción de base de datos (DB::beginTransaction() en InvoiceController@store). Si falla la creación de un solo detalle (invoice_details), toda la boleta se revierte (DB::rollBack), garantizando que no haya registros incompletos o erróneos.4. Manejo de Precios UnitariosLa tabla invoice_details guarda el campo precio_unitario_final. Este es el precio que se muestra en la boleta, asegurando que si el precio base del servicio cambia mañana, la boleta histórica mantiene el precio cobrado originalmente.
