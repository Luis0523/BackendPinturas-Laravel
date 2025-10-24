# Backend Plataforma de Pinturas - Laravel API REST

## 📋 Descripción del Proyecto

Backend desarrollado en **Laravel 12** para una plataforma de gestión y venta de pinturas. Este proyecto es una migración desde Node.js/Express hacia PHP/Laravel, manteniendo la misma estructura modular y funcionalidades del sistema original.

El sistema maneja:
- Catálogo de productos (pinturas, marcas, categorías, presentaciones)
- Gestión de inventario por sucursal
- Compras y proveedores
- Ventas y facturación
- Usuarios y clientes
- Campañas de marketing
- Auditoría completa del sistema

---

## 🏗️ Arquitectura del Proyecto

### Estructura Modular

El proyecto sigue una arquitectura modular inspirada en el backend original de Node.js, organizando los componentes por dominio de negocio:

```
app/
├── Models/
│   ├── Core/                 # Entidades fundamentales del sistema
│   │   ├── Marca.php         ✅ Implementado
│   │   ├── Categoria.php     ✅ Implementado
│   │   ├── Presentacion.php  ⏳ Pendiente
│   │   ├── Rol.php           ⏳ Pendiente
│   │   └── Sucursal.php      ⏳ Pendiente
│   │
│   ├── Productos/            # Productos y relaciones
│   │   ├── Producto.php      ✅ Implementado
│   │   └── ProductoPresentacion.php  ⏳ Pendiente
│   │
│   ├── Compras/              # Módulo de compras (5 modelos pendientes)
│   ├── Inventario/           # Control de inventario (3 modelos pendientes)
│   ├── Usuarios/             # Usuarios del sistema (2 modelos pendientes)
│   └── Ventas/               # Módulo de ventas (3 modelos pendientes)
│
└── Http/Controllers/Api/
    ├── Core/
    │   ├── MarcaController.php       ✅ Implementado
    │   ├── CategoriaController.php   ✅ Implementado
    │   └── ...
    │
    ├── Productos/
    │   ├── ProductoController.php    ✅ Implementado
    │   └── ...
    │
    ├── Compras/              ⏳ Pendiente
    ├── Inventario/           ⏳ Pendiente
    ├── Usuarios/             ⏳ Pendiente
    └── Ventas/               ⏳ Pendiente
```

---

## 🗄️ Base de Datos

### Estado de Migraciones

✅ **33 tablas** ya ejecutadas con datos de prueba

### Módulos en Base de Datos:

- **Core:** roles, sucursales, marcas, categorias, presentaciones
- **Productos:** productos, productopresentacion
- **Compras:** proveedores, ordenes_compra, recepciones (+ detalles)
- **Inventario:** inventariosucursal, movimientosinventario, precios
- **Usuarios:** usuarios, clientes
- **Ventas:** facturas, detallefactura, pagos
- **Marketing:** campanias, campania_destinatarios, campania_adjuntos
- **Auditoría:** logs_sistema

---

## 🚀 Modelos y Controladores Implementados

### ✅ Módulo Core - Marcas

**Modelo:** `App\Models\Core\Marca`

**Relaciones:**
- `hasMany(Producto::class)` - Una marca tiene muchos productos

**Endpoints:**
```
GET    /api/marcas          - Listar todas
GET    /api/marcas/{id}     - Obtener una
POST   /api/marcas          - Crear nueva
PUT    /api/marcas/{id}     - Actualizar
DELETE /api/marcas/{id}     - Eliminar
```

---

### ✅ Módulo Core - Categorías

**Modelo:** `App\Models\Core\Categoria`

**Relaciones:**
- `hasMany(Producto::class)` - Una categoría tiene muchos productos

**Endpoints:**
```
GET    /api/categorias          - Listar todas
GET    /api/categorias/{id}     - Obtener una
POST   /api/categorias          - Crear nueva
PUT    /api/categorias/{id}     - Actualizar
DELETE /api/categorias/{id}     - Eliminar
```

---

### ✅ Módulo Productos - Productos

**Modelo:** `App\Models\Productos\Producto`

**Campos principales:**
- `codigo_sku` - Código único del producto
- `descripcion` - Descripción
- `tamano`, `duracion_anios`, `extension_m2`, `color`
- `marca_id`, `categoria_id` (Foreign Keys)

**Relaciones:**
- `belongsTo(Marca::class)` - Pertenece a una marca
- `belongsTo(Categoria::class)` - Pertenece a una categoría

**Endpoints:**
```
GET    /api/productos          - Listar todos (con marca y categoría)
GET    /api/productos/{id}     - Obtener uno
POST   /api/productos          - Crear nuevo
PUT    /api/productos/{id}     - Actualizar
DELETE /api/productos/{id}     - Eliminar
```

**Características:**
- ✅ Eager Loading automático de marca y categoría
- ✅ Validación de foreign keys
- ✅ Timestamps personalizados (createdAt, updatedAt)

---

## 📡 API REST

### Formato de Respuestas

**Éxito:**
```json
{
  "success": true,
  "data": { ... }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Descripción del error"
}
```

### Códigos HTTP

- `200` - OK
- `201` - Created
- `404` - Not Found
- `409` - Conflict (integridad referencial)
- `422` - Validation Error

---

## 🛠️ Instalación

### Requisitos
- PHP 8.2+
- Composer
- MySQL 5.7+
- Laravel 12

### Pasos

1. **Clonar e instalar:**
```bash
git clone <url>
cd backend-pinturas
composer install
```

2. **Configurar base de datos:**
```bash
cp .env.example .env
# Editar .env con tus credenciales
php artisan key:generate
```

3. **Ejecutar migraciones:**
```bash
php artisan migrate
```

4. **Iniciar servidor:**
```bash
php artisan serve
```

API disponible en: `http://127.0.0.1:8000/api`

---

## 🧪 Ejemplos de Uso

**Listar productos:**
```bash
curl http://127.0.0.1:8000/api/productos
```

**Crear marca:**
```bash
curl -X POST http://127.0.0.1:8000/api/marcas \
  -H "Content-Type: application/json" \
  -d '{"nombre": "Nueva Marca", "activa": true}'
```

---

## 📊 Progreso del Proyecto

### ✅ Completado

- [x] Instalación y configuración de Laravel 12
- [x] Migraciones ejecutadas (33 tablas)
- [x] Estructura modular organizada
- [x] Módulo Core: Marcas (CRUD completo)
- [x] Módulo Core: Categorías (CRUD completo)
- [x] Módulo Productos: Productos (CRUD completo)
- [x] Rutas API configuradas
- [x] Validaciones y manejo de errores
- [x] Eager Loading en relaciones

### ⏳ Siguiente Fase

**Completar Módulo Core:**
1. Presentacion (presentaciones de productos)
2. Rol (roles de usuario)
3. Sucursal (sucursales con GPS)

**Luego:**
- ProductoPresentacion (catálogo vendible)
- Módulo Usuarios
- Módulo Inventario
- Módulo Compras
- Módulo Ventas

---

## 📝 Convenciones

- **Modelos:** PascalCase en carpetas modulares
- **Controladores:** PascalCase + "Controller"
- **Tablas:** snake_case en minúsculas
- **Métodos REST:** index, store, show, update, destroy
- **Respuestas:** JSON estandarizado con success/data/message

---

## 📞 Información

**Stack Tecnológico:**
- Laravel 12
- PHP 8.2+
- MySQL
- Eloquent ORM

**Migración desde:**
- Node.js + Express + Sequelize

**Última actualización:** 24 de octubre de 2025
**Versión:** 0.1.0-alpha
