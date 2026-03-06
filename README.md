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

**ProyectoServicios** es una plataforma web moderna, resiliente e integral, concebida específicamente para administrar la información de los clientes, estandarizar un catálogo de servicios y unificar la **emisión de comprobantes de pago**. El sistema genera su facturación exclusivamente bajo el formato de **Boletas de Venta (formato A4 corporativo)** y **Tickets Térmicos de 80mm** aptos para entrega inmediata al cliente en Punto de Venta.

A diferencia de los sistemas de facturación tradicionales que están orientados únicamente al comercio minorista (donde se controla estrictamente unidades y metros cuadrados de inventario), esta plataforma ha sido **desarrollada exclusivamente en torno al rubro de servicios corporativos e independientes**. Su modelo de persistencia facilita que los agentes comerciales puedan modificar dinámicamente el valor del servicio directamente en la caja, registrando anticipos (estados pendientes) o liquidaciones inmediatas. Todo esto entregado bajo una experiencia de interfaz amigable, predecible y fundamentalmente responsiva para cualquier pantalla.

---

## ✨ Características y Funcionalidades Clave

### 1. Panel Cero-Pérdida (Papeleras de Reciclaje / SoftDeletes)
En un entorno comercial acelerado, los errores humanos y los borrados accidentales son inevitables. Para mitigar esta debilidad, el sistema integra la funcionalidad de `SoftDeletes` del ORM de Laravel en todas sus entidades clave: Clientes, Servicios del catálogo, Emisión de Boletas y Perfiles de Usuario. Al hacer clic en "Eliminar", el registro desaparece de la vista del operador común pero se almacena de forma invisible. Un Administrador posee un panel superior dedicado (Papelera) desde el que audita estos movimientos, pudiendo devolver la información instantáneamente a su estado original o pulverizarla por completo (Force Delete) previa validación en un modal de seguridad.

### 2. Diseño 100% Responsivo y Mobile-First
La interfaz no es un mero "template", sino un diseño construido bloque a bloque con clases utilitarias de Tailwind CSS. Dependiendo del tipo de pantalla de lectura (ya sea un teléfono móvil en campo, una tablet de sala de espera o la computadora principal de mostrador), el flujo completo se reacomoda dinámicamente y las tablas colapsan inteligentemente mediante scroll horizontal y tarjetas apiladas, preservando la experiencia (UX) sin cortar ni esconder ninguna información.

### 3. Motor de Comprobantes Multi-formato a Prueba de Fallos
La plataforma usa `DomPDF` para exportar automáticamente y generar facturas en distintos formatos físicos. Abarca vistas clásicas desde navegadores (Web), el tradicional **PDF A4** de contabilidad corporativa, y la popular **Tira de Ticket Térmico** de 80mm de Punto de Venta (POS). Para sistemas Windows, se ha inyectado una conversión base64, dotando resiliencia absoluta de renderizado de logotipos sin importar permisos de estructura o la ruta `Storage`.

### 4. Gestión Ágil de Servicios y Precios Modificables al Momento
Mantiene un catálogo base, pero no limita al vendedor en las negociaciones comerciales. Mientras se realiza la venta, los "Precios Base" catalogados pueden ser sobreescritos al capricho del vendedor sin que el catálogo maestro se maltiplique ni altere. Suma un modelo sin stock: ya que los servicios no ocupan lugar físico, la plataforma te quita la presión de hacer "ingresos de mercancía" para poder vender.

### 5. Estados de Cobranza (Control de Adelantos y Deudas)
Implementa nativamente soporte de facturación a tres cortes comerciales:
- **Pagada:** El flujo normal, cierre total liquidado al entregar recibo.
- **Pendiente / A Cuenta:** Permite registrar un servicio que durará varios días dejando un depósito adelantado parcial de parte del cliente. Al registrarlo, el sistema calcula obligatoriamente el saldo pendiente a cobrar a futuro e incrusta los términos como notas al pie en los reportes del ticket.
- **Anulada:** En caso de refacturaciones y correcciones, se anula el recaudo contable manteniendo intacto un expediente y número de boleta secuencial válido para la auditoría técnica.

### 6. Configuración Corporativa y Cierres de Caja Inmutables
Un panel único para subir y recortar logos, actualizar tributos variables como tasas de impuesto agregada (IVA/IGV) o símbolo dinámico de monedas, sin que dependas del ingeniero de software. Las agrupaciones son inmutables: Cuando finaliza la jornada el operador descarga un *"Corte de Caja Diario"* separando montos parciales de los totales.

---

## 📋 Requisitos del Sistema (Ingeniería)

### Requerimientos Funcionales Documentados (RF)
- **RF1. Punto de Venta Dinámico (POS):** El operario debe ser capaz de componer boletas permitiendo alteraciones unitarias directas en los listados del carrito, generando una suma automática y reasignación de recargos sin refrescar el DOM.
- **RF2. Auditoría Preventiva y Restauración:** Todo registro eliminado generará un estado lógico ausente. Solo los perfiles jerárquicos de nivel "Administrador" observarán el listado global de entidades depuradas con opciones de restauración y saneamiento (Papeleras). 
- **RF3. Registro Estricto de Depósitos Múltiples:** Generación nativa de comprobantes pre-pago (Estado: Pendiente) con trazabilidad y campos de observaciones limitados donde el operador puede registrar los acuerdos del anticipo y leer el adeudo automático al final de la operación.
- **RF4. Modelo Inmutable de Logos Institucionales:** Toda salida pre-compilada, independientemente de que se llame de una vista web `blade.php` o una cabecera para render en PDF, apuntará a un proveedor único universal para evitar discrepancias gráficas cuando se reemplacen credenciales y sellos institucionales de la empresa.

### Requerimientos No Funcionales Cruciales (RNF)
- **RNF1. Base Transaccional Estricta (ACID):** Por el resguardo del historial de ventas, un fracaso o corte en internet durante el llenado de 50 ítems en un carrito, no debe causar comprobantes huérfanos. Se ejecutarán comandos de tipo `DB::beginTransaction()` para envolver bloqueos. O todo funciona, o ninguna fila extraña queda en las métricas.
- **RNF2. Reactividad UI Subyacente:** La plataforma adoptará `Alpine.js` a modo de framework liviano y complementario al backend para asegurar transformaciones matemáticas instantáneas del IVA, evitando cargas pesadas entre llamadas de servidor-cliente en la pantalla de cobros.
- **RNF3. Adaptabilidad Visual Universal:** Elementario a los nuevos flujos tecnológicos, la interfaz tiene la responsabilidad ineludible de ajustarse mediante CSS y Flexbox al 100% de los anchos de monitores corporativos HD, netbooks limitadas de mostrador, y smatphones Android/iOS para trabajadores técnicos remotos.

---

## 🎭 Casos de Uso Empresarial (UML Operacional Orientado al Flujo)

### CU01: Facturación y Negociación In Situ
1. Un cliente recurrente negocia el "Mantenimiento Preventivo de Servidor".
2. El *Cajero Operativo* genera una boleta y selecciona al cliente sin necesidad de crearlo (usando buscador de autocompletado en el sistema).
3. Agrega al carrito el mantenimiento listado a $200.00 en el catálogo base, pero sobreescribe su valor numérico a $180.00 debido al acuerdo que firmaron en la llamada, finalizando bajo estatus `Pagada`.
4.  La base de datos graba el nuevo precio en el historial sin afectar al catálogo de los demás clientes. La impresora automática libera el Comprobante A4 en PDF.

### CU02: Tratamiento de Servicios Largos - Modelo Adelantos
1. Se capta el encargo del rearmado total de motores de un vehículo accidentado.
2. Como se necesitan insumos preliminares, el empleado confecciona el carrito indicando estatus `Pendiente` para certificar el inicio.
3. El frontend despliega en forma animada los `Términos, Condiciones y Adelanto`. El cliente deja 1500 USD como respaldo inicial para comprar material de los 3500 final.
4. El PDF de salida del recibo térmico informa de forma imponible el pago base y especifica que tiene *"Saldo Pendiente Deudor de $2,000 USD"* y sus condiciones anotadas en los comentarios de la descripción de espera.

### CU03: Refacturaciones, Cierres Diarios y el Botón de Rescate
1. Llega el viernes por la noche y un empleado operativo marca un ticket mal que no debió cobrar, anulando su cierre contable.
2. Emite la acción de *Eliminar*. Y su contabilidad del día vuelve a encuadrarse.
3. Genera finalmente su documento de `Reportes Diarios` para rendir su cuenta al encargado de su turno cruzando información de sus `Cajas Pagadas y Depósitos de Adelantos Exitosos`.
4. El encargado corporativo entra, revisa bajo `Papeleras` la boleta oculta, coteja que el empleado cometió la eliminación para cuadrar su caja y procede a realizar la purga destructiva (Force Delete) terminando todo un ciclo transaccional sano.

---

## 🛡️ Arquitectura y Stack Tecnológico Detallado

El andamiaje técnico de los micro-componentes y las dependencias estructurales estipula:

- **Almacenamiento y Concurrencia Relacional:** Operar exclusivamente sobre conectores transaccionales **MySQL 8+** (o el gemelo MariaDB). Todo modelo foráneo tiene claves restrictas y cascadas controladas.
- **Núcleo Back-End Orientado al MVC:** Desarrollado bajo Laravel 12.x en la versión PHP dinámica (Mínimo `>= 8.2`). Emplea la arquitectura y convenciones inamovibles de inyección de controladores limpios.
- **Precios Históricos Concurridos (Inmutabilidad):** Los importes base son fotos momentáneas. Al momento de generar la grabación de las `Invoices_Details`, se desacoplan del modelo principal `Services` garantizando que las modificaciones futuras del listado normal dictadas por la inflación jamás editen retrospectivamente montos ganados años anteriores.
- **Framework de Vista (View-Layer):** Uso extensivo del compilador moderno Vite junto con directivas Reactivas `Alpine.js` y clases de `Tailwind 3` minimizadas.

---

## 👥 Permisos Preformados Jerárquicamente (Roles de Accesos)

El sistema estipula tres niveles de perfiles de control delimitados internamente basados en los Seeders maestros provistos durante el primer despliegue:

| Identificador de Sistema | Nivel Organizacional y Atribuciones Permitidas |
|----------------------|-----------------------------------------------|
| `admin` | **Directiva y Administración Estratégica:** Nivel superusuario. Tienen potestad privativa de visitar las **Papeleras de Recuperación y Forzado destructivo**. Son únicamente ellos quienes cambian las insignias fiscales, la tasa impositiva central y depuran boletas falsamente ejecutadas. |
| `trabajador` | **Gestores de Puntos de Venta (POS Cajero):** Los vendedores en terreno. Realizan interacciones diarias para añadir clientes, concretar cobros totales o registrar las boletas pendientes. Únicamente reportan sus propias utilidades del turno actual diario para el recuento. |
| `usuario` | **Cuentas Restringidas e Invitados:** Elementos neutralizados del panel logístico que carecen de impacto fincanciero. Para consultas de solo-lectura temporal. |

---

## 🔄 Relevancia Histórica de Versiones (Changelog)

### **v1.6.0 (Actual - Reestructuración Fuerte de Flujos)**
- **Feature [Core]:** Implantación definitiva del sistema `SoftDeletes` modulares (Papeleras) permitiendo la reconstrucción en una interfaz visible e individualizada.
- **Módulo Transaccional:** Implementandos formalmente los campos de *"Adelantos a Cuenta y Saldos Residuales por Cobrar"* dotando de la verdadera naturaleza a las notas informativas para Servicios demorados a nivel visual e interno en PDF.
- **Mejora de UX Móvil:** Conversión total de visuales tipo tabla a layouts tipo *Card* e inyecciones de CSS Responsivo para operación móvil e interactividad `Mobile-First`.
- **Hotfix (Critical):** Corrección masiva de la lectura y subida universal de Logotipos (Issue de inyectores vacíos en ambiente Windows). Ahora el `AppServiceProvider` serializa imágenes garantizando reportes térmicos y PDF perpetuamente limpios.

### **v1.3.x a v1.5.0**
- Rediseño mayor del catálogo interactivo. Separación contundente de un modelo de negocio de facturación comercial físico transicionándolo al control libre de precios `in situ`. Exclusión general de módulos limitantes de entrada/salida de stock en favor de la agilidad.

### **v1.0.0**
- Núcleo monolítico transitorio montado tras autenticación Laravel Breeze estándar y perfiles crud monobloque.

---

## ⚙️ Guía Estricta de Despliegue en Servidores Producción

### Prerrequisitos Computacionales de Entorno
- Sistema Servidor Web (Apache/Nginx o derivado Litespeed)
- Runtime PHP `>= 8.2` y obligatoriamente habilitadas las extensiones sub-sistémicas **(Ext-GD activado e Intl)**.
- Clúster o Servidor Individual de **MySQL >= 8.0**.
- Gestores de Paquete Node.js y npm (`>= v18`) e Intérprete Composer de Back-End.

### Procedimientos de Levantamiento Total

**1. Despliegue Directo de Repositorio**
En un entorno git autorizado o terminal de servidor ejecute la invocación directa y entra al directorio clonado:
```bash
git clone https://github.com/paraZmol/ProyectoServicios.git
cd ProyectoServicios
```

**2. Descarga de Librerías y Módulos de Núcleo (Backend/Frontend)**
Evite variables desarrollo en prod. Instale de forma optimizada. Posterior compile la maqueta del diseño con Vite.
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

**3. Set-eo de Configuración Criptográfica y Claves**
Deberá copiar integralmente la estrcutura de entorno base. Establezca la llave general de sesión en un movimiento nativo y actualice dentro del archivo las variables `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, asegurando estar sobre conexión estricta de `mysql`.
```bash
cp .env.example .env
php artisan key:generate
```

**4. Estructuración y Población Inicial MySQL (Arquitectura)**
Dispara toda la arquitectura de Tablas foráneas hacia tu Base de Datos previamente enlazada y configurada, llenando tu sistema mediante Semillas base de un primer Administrativo e Impuestos globales por defecto a rellenar:
```bash
php artisan migrate:fresh --seed
```

**5. Habilitación de Pila Pública y Logos Múltiples (CRÍTICO)**
De no realizarse este comando en específico de enlazar symlink, todas sus visualizaciones subidas intermitentes mediante el panel de ajuste y de sistema estarán temporalmente corruptas e irrecuperables en PDFs locales.
```bash
php artisan storage:link
```

Si toda la cascada de compilación resultó satisfactoria. El aplicativo subyacente está listo para alojarse. En un VPS enrute su DocumentRoot o simplemente ejecute `php artisan serve` si está finalizando su pre-test en local-machine.

---
*Respaldado por metodologías sólidas para desempeño superior en Software Point-of-Sale.*
