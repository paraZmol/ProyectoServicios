<div align="center">
  <img src="public/img/logo_default.png" alt="Logo del Proyecto" width="150"/>
  <h1>ProyectoServicios v1.6.0</h1>
  <p><em>Sistema Profesional de Gestión Operativa y Emisión de Boletas de Servicios</em></p>

  [![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)](https://www.php.net/)
  [![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
  [![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.1-38B2AC?logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
  [![Alpine.js](https://img.shields.io/badge/Alpine.js-3.4-8BC0D0?logo=alpine.js&logoColor=white)](https://alpinejs.dev/)
</div>

---

## 📖 Descripción del Proyecto

**ProyectoServicios** es una plataforma web bastante moderna y completa, pensada especialmente para que administres la información de tus clientes, tengas tu catálogo de servicios ordenado y unifiques toda la **emisión de comprobantes de pago**. El sistema genera sus facturas exclusivamente como **Boletas de Venta - formato A4 corporativo -** y **Tickets Térmicos de 80mm** que están listos para entregar al cliente en tu mostrador o punto de venta.

A diferencia de los sistemas de facturación de toda la vida que están pensados solo para tiendas o bodegas - donde te obligan a llevar un control estricto de unidades y stock -, esta plataforma fue **creada exclusivamente para los negocios que ofrecen servicios**. Su forma de trabajar permite que tus vendedores o cajeros puedan cambiar el precio de un servicio al instante justo al momento de cobrar, registrar adelantos de dinero o hacer cobros inmediatos. Todo esto desde una pantalla muy fácil de usar y que además se adapta perfectamente a tu celular o tablet.

---

## ✨ Características y Funcionalidades Clave

### 1. Panel Cero Pérdida - Papeleras de Reciclaje -
En un negocio con mucho movimiento, los errores humanos o borrar cosas sin querer van a pasar sí o sí. Para evitar dolores de cabeza, el sistema tiene una "Papelera" mágica para todo lo importante: Clientes, Servicios, Boletas y Usuarios. Si alguien hace clic en "Eliminar", el elemento desaparece de la vista del trabajador pero se esconde de forma segura. Solo un Administrador puede entrar al menú de Papeleras escondidas, revisar qué pasó y devolver la información a donde estaba, o borrarla para siempre de existir confirmación.

### 2. Diseño 100% Adaptable para Celulares
La pantalla no es una plantilla comprada, sino un diseño hecho a medida. Dependiendo de dónde lo abras - ya sea el celular de un técnico en la calle, una tablet o la computadora de la caja -, toda la pantalla se reacomoda sola. Las tablas grandes se achican de manera inteligente para que puedas deslizar y no pierdas ningún dato valioso.

### 3. Emisión de Tickets y Boletas a Prueba de Fallos
El sistema utiliza tecnología especial para exportar y crear tus comprobantes sin importar dónde lo abras. Cubre la vista desde la página web, el clásico **PDF tamaño A4** para tus cuentas formales, y la famosa **Tira de Ticket Térmico** de 80mm. Además, si usas Windows, tus logotipos de empresa se transforman en códigos imborrables para que los recibos nunca salgan con la imagen rota.

### 4. Trabajo Rápido y Precios Flexibles
Tienes tu lista de servicios base, pero esto no limita a tus vendedores a la hora de negociar con un cliente. Mientras están armando la venta, pueden cambiar el precio de tu catálogo al momento sin malograr la lista original. Como vendes servicios y no cosas físicas, el sistema te quita encima ese peso de tener que "ingresar stock" para poder trabajar.

### 5. Control de Pagos, Adelantos y Deudas
El sistema maneja tres estados de facturación que te solucionan la vida:
- **Pagada:** El flujo normal donde te liquidan todo el dinero ahí mismo y entregas el recibo.
- **Pendiente o A Cuenta:** Súper útil si un servicio tomará varios días y el cliente te deja un depósito de adelanto. Al anotarlo, el sistema calcula de inmediato cuánto dinero te debe el cliente e inyecta estas cuentas claras en los comentarios del ticket impreso.
- **Anulada:** Si te equivocas cobrando, simplemente anulas la boleta. El dinero falso se borra de tus ganancias del día pero el papel de la boleta queda guardado para que tu caja no tenga huecos extraños.

### 6. Configuración Sencilla de tu Empresa
Hay una pantalla única para que subas tu logo corporativo o cambies el porcentaje del impuesto - como el IGV o IVA - y el símbolo de tu moneda sin que tengas que llamar a un programador. Y algo clave: cuando termine el día, tus empleados pueden descargar un "Corte de Caja Diario" que les separa cuánto dinero entró en efectivo directo y cuánto entró por adelantos de clientes.

---

## 📋 Requisitos del Sistema

### Lo que hace el sistema por ti - Funcionalidades -
- **Caja Rápida:** Tu cajero puede armar la boleta, cambiarle los precios al vuelo y ver cómo se suma el total automáticamente sin que la pantalla cargue o parpadee.
- **Botón de Pánico Administrativo:** Si borran boletas, el jefe del negocio tiene un menú escondido tipo papelera de reciclaje para salvar la situación.
- **Control de Adelantos:** Puedes crear boletas pendientes y asentar por escrito qué trato hiciste con el cliente y cuánto te dio de anticipo.
- **Imágenes Perfectas:** Tus logos salen nítidos e iguales ya sea en el sistema web, impresos o en PDF.

### Lo técnico bajo el capó
- **Base Súper Segura:** Todo funciona sobre MySQL. Si se corta el internet mientras alguien te cobra 50 ítems, el sistema bloquea todo para que no se guarde una boleta a medias que arruine tus cuentas.
- **Matemáticas Instantáneas:** Usamos Alpine.js para que los impuestos y totales reaccionen como calculadora rápida en la misma página de ventas.
- **Responsive:** Se ajusta a monitores gigantes de oficina o pantallas pequeñas.

---

## 🎭 Casos de Uso Diarios - Ejemplos de Trabajo -

### Ejemplo 1: Cobrando con un Descuento Rápido
1. Un cliente de siempre viene por su Mantenimiento de Servidor.
2. Tu trabajador abre la boleta y busca al cliente - sin tener que volver a registrarlo -.
3. El mantenimiento cuesta $ 200, pero conversando acuerdan dejarlo a $ 180. El trabajador le cambia el precio ahí mismo y la cobra como Pagada.
4.  El sistema guarda la venta a $ 180 pero mantiene tu catálogo oficial intacto a $ 200. Sale la boleta A4 en PDF lista para imprimir.

### Ejemplo 2: Un Trabajo Largo con Dinero de Adelanto
1. Te dejan un auto para un trabajo profundo e ingresas los primeros detalles.
2. Como se necesitan comprar los repuestos de inicio, el empleado crea la boleta pero la marca como Pendiente.
3. El sistema muestra la zona para asentar el dinero adelantado. El cliente deja 1500 USD de los 3500 que cuesta todo y firma los términos.
4. El Ticket Térmico sale impreso avisándole al cliente de forma transparente que tiene un "Saldo Deudor de $ 2000" para que no haya confusiones al momento de recoger.

### Ejemplo 3: Equivocaciones en la Caja y Reporte del Día
1. Es viernes por la tarde y un empleado marca mal una boleta que no debía cobrar. 
2. Para que no le falte dinero en el bolsillo, le da a "Eliminar" y su caja vuelve a cuadrar.
3. Al acabar el turno aprieta el botón de Reporte Diario y el sistema le da un PDF donde le separa todo el dinero bueno que ganó de forma directa y los adelantos.
4. Luego entras tú - el Administrador - vas a la pestaña Papeleras, ves la boleta que anuló el chico, verificas que lo hizo bien para salvar la caja y decides destruirla definitivamente para limpiar el sistema.

---

## 🛡️ Detalles de Tecnología 

Esta es la ingeniería que soporta el programa:

- **Almacenamiento Confiable:** Exige trabajar con MySQL 8 o su gemelo MariaDB. Todo está amarrado para evitar errores de red.
- **Cerebro del Sistema:** Construido en Laravel 12 con PHP 8. Está programado con un orden impecable para inyectar todas las reglas de negocio.
- **Precios Congelados en el Tiempo:** Si mañana subes todos los precios en tu negocio por la inflación, no te preocupes. Tus boletas de hace tres años quedarán con el precio exacto con el que fueron vendidas.
- **Vista Moderna:** Hecho con Vite, herramientas reactivas y clases especiales de Tailwind 3 que lo hacen ligero.

---

## 👥 Niveles de Acceso y Roles

El negocio funciona con tres perfiles que vienen listos para usarse:

| Nivel que manejas | Lo que puedes hacer |
|----------------------|-----------------------------------------------|
| `admin` | **El Jefe:** Ingresas a todo. Eres el único con pase VIP a las Papeleras de Reciclaje para destruir o perdonar boletas eliminadas. Solo tú subes el logo de la empresa y marcas cuánto se cobra de impuestos. |
| `trabajador` | **El que atiende:** Tus vendedores solo pueden cobrar boletas, asentar adelantos o agregar nuevos clientes fijos. Además, a la hora de descargar su dinero del día solo pueden ver SU propio dinero y no el de los demás cajeros. |
| `usuario` | **Cuentas Limitadas:** Un perfil neutro pensado por si quieres que alguien mire el sistema de manera muy restringida sin tocar el dinero ni hacer boletas. |

---

## 🔄 Últimas Mejoras 

### **v1.6.0 - Actual -**
- **Novedad Principal:** Añadidas las Papeleras de Reciclaje. Ahora puedes recuperar absolutamente todo sin entrar en pánico si alguien se equivoca borrando cosas.
- **Adelantos Completos:** Se mejoró muchísimo la opción de "A Cuenta". Ahora el sistema maneja recibos de Saldo por Cobrar con avisos y notas especiales en los PDFs.
- **Súper Celular:** Ahora la vista en tu móvil se ve mejor que nunca. Transformamos todas esas tablas apretadas en tarjetas apiladas para que trabajes cómodo en la calle.
- **Solución a Logotipos:** Se acabó el dolor de cabeza de los logos en blanco para sistemas instalados en Windows. El sistema ahora crea una versión de tu foto de empresa súper resistente para que los PDF siempre salgan pintados.

### **v1.3.x a v1.5.0**
- Dejamos atrás la facturación aburrida de inventario y nos pasamos a la gestión de servicios donde todo el precio es libre para cobrar como un verdadero punto de venta in situ.

### **v1.0.0**
- Nuestro primer sistema de control básico con el ingreso inicial de usuarios.

---

## ⚙️ Guía de Instalación Rápida para Producción

### Requisitos Mínimos
- Servidor web - Apache, Nginx o Litespeed -
- PHP 8.2 en adelante - Con la extensión GD encendida sí o sí para que tus logos funcionen -
- Base de datos MySQL 8.0 en adelante.
- Tener instalado Node.js y Composer.

### Pasos para Arrancar

**1. Bajar el Código**
Pon esto en tu terminal o sistema:
```bash
git clone https://github.com/paraZmol/ProyectoServicios.git
cd ProyectoServicios
```

**2. Descargar Librerías**
Instalaremos todas las partes del motor de Laravel y construiremos la parte visual:
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

**3. Archivo de Secretos**
Haz una copia del `.env.example`, ponle de nombre `.env` y genera la contraseña maestra. No olvides entrar al archivo creado y poner los datos reales de tu base de datos MySQL.
```bash
cp .env.example .env
php artisan key:generate
```

**4. Crear Toda Tu Base de Datos**
Este comando inyectará todas las tablas de facturación, clientes e incluso las cuentas base de administrador:
```bash
php artisan migrate:fresh --seed
```

**5. El Comando Vida o Muerte para las Imágenes**
Asegúrate de ejecutar esta línea, si no lo haces, los logotipos que subas desde el panel principal no saldrán nunca en tus facturas:
```bash
php artisan storage:link
```

Si toda la instalación fue bien, ¡El programa está listo para recibir clientes! Si lo haces en tu máquina propia solo ejecuta `php artisan serve`.

---
*Hecho pensando en brindarte agilidad y cuidar todo el flujo de tu dinero.*
