<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Core\MarcaController;
use App\Http\Controllers\Api\Core\CategoriaController;
use App\Http\Controllers\Api\Core\PresentacionController;
use App\Http\Controllers\Api\Core\RolController;
use App\Http\Controllers\Api\Core\SucursalController;
use App\Http\Controllers\Api\Productos\ProductoController;
use App\Http\Controllers\Api\Productos\ProductoPresentacionController;
use App\Http\Controllers\Api\Usuarios\ClienteController;
use App\Http\Controllers\Api\Usuarios\UsuarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas API REST para la plataforma de pinturas
| Todas las rutas tienen el prefijo /api automáticamente
|
| Estructura modular:
| - Core: Marcas, Categorías, Presentaciones, Roles, Sucursales
| - Productos: Productos, ProductoPresentacion
| - Compras: Proveedores, Órdenes de Compra, Recepciones
| - Inventario: Inventario por Sucursal, Movimientos, Precios
| - Usuarios: Usuarios, Clientes
| - Ventas: Facturas, Detalles, Pagos
|
*/

// ==================== RUTAS CORE ====================
// Marcas
Route::get('/marcas', [MarcaController::class, 'index']);           // Listar todas
Route::get('/marcas/{id}', [MarcaController::class, 'show']);       // Obtener una
Route::post('/marcas', [MarcaController::class, 'store']);          // Crear nueva
Route::put('/marcas/{id}', [MarcaController::class, 'update']);     // Actualizar
Route::delete('/marcas/{id}', [MarcaController::class, 'destroy']); // Eliminar

// Categorías
Route::get('/categorias', [CategoriaController::class, 'index']);           // Listar todas
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);       // Obtener una
Route::post('/categorias', [CategoriaController::class, 'store']);          // Crear nueva
Route::put('/categorias/{id}', [CategoriaController::class, 'update']);     // Actualizar
Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']); // Eliminar

// Presentaciones
Route::get('/presentaciones', [PresentacionController::class, 'index']);              // Listar activas
Route::get('/presentaciones/all', [PresentacionController::class, 'all']);            // Listar todas
Route::get('/presentaciones/{id}', [PresentacionController::class, 'show']);          // Obtener una
Route::post('/presentaciones', [PresentacionController::class, 'store']);             // Crear nueva
Route::put('/presentaciones/{id}', [PresentacionController::class, 'update']);        // Actualizar
Route::delete('/presentaciones/{id}', [PresentacionController::class, 'destroy']);    // Desactivar
Route::patch('/presentaciones/{id}/reactivar', [PresentacionController::class, 'reactivar']); // Reactivar

// Roles
Route::get('/roles', [RolController::class, 'index']);           // Listar todos
Route::get('/roles/{id}', [RolController::class, 'show']);       // Obtener uno
Route::post('/roles', [RolController::class, 'store']);          // Crear nuevo
Route::put('/roles/{id}', [RolController::class, 'update']);     // Actualizar
Route::delete('/roles/{id}', [RolController::class, 'destroy']); // Eliminar

// Sucursales
Route::get('/sucursales', [SucursalController::class, 'index']);              // Listar activas
Route::get('/sucursales/all', [SucursalController::class, 'all']);            // Listar todas
Route::get('/sucursales/cercanas', [SucursalController::class, 'cercanas']);  // Buscar cercanas por GPS
Route::get('/sucursales/{id}', [SucursalController::class, 'show']);          // Obtener una
Route::post('/sucursales', [SucursalController::class, 'store']);             // Crear nueva
Route::put('/sucursales/{id}', [SucursalController::class, 'update']);        // Actualizar
Route::delete('/sucursales/{id}', [SucursalController::class, 'destroy']);    // Desactivar
Route::patch('/sucursales/{id}/reactivar', [SucursalController::class, 'reactivar']); // Reactivar

// ==================== RUTAS PRODUCTOS ====================
// Productos
Route::get('/productos', [ProductoController::class, 'index']);           // Listar todos
Route::get('/productos/{id}', [ProductoController::class, 'show']);       // Obtener uno
Route::post('/productos', [ProductoController::class, 'store']);          // Crear nuevo
Route::put('/productos/{id}', [ProductoController::class, 'update']);     // Actualizar
Route::delete('/productos/{id}', [ProductoController::class, 'destroy']); // Eliminar

// ProductoPresentacion (Tabla pivote Producto + Presentación)
Route::get('/producto-presentacion', [ProductoPresentacionController::class, 'index']);                     // Listar activas
Route::get('/producto-presentacion/all', [ProductoPresentacionController::class, 'all']);                   // Listar todas
Route::get('/producto-presentacion/catalogo', [ProductoPresentacionController::class, 'catalogo']);         // Catálogo vendible
Route::get('/producto-presentacion/producto/{id}', [ProductoPresentacionController::class, 'porProducto']); // Presentaciones de un producto
Route::get('/producto-presentacion/{id}', [ProductoPresentacionController::class, 'show']);                 // Obtener una
Route::post('/producto-presentacion', [ProductoPresentacionController::class, 'store']);                    // Crear nueva
Route::post('/producto-presentacion/batch', [ProductoPresentacionController::class, 'storeBatch']);         // Crear múltiples
Route::put('/producto-presentacion/{id}', [ProductoPresentacionController::class, 'update']);               // Actualizar
Route::delete('/producto-presentacion/{id}', [ProductoPresentacionController::class, 'destroy']);           // Desactivar
Route::patch('/producto-presentacion/{id}/reactivar', [ProductoPresentacionController::class, 'reactivar']); // Reactivar

// ==================== RUTAS USUARIOS ====================
// Clientes
Route::get('/clientes', [ClienteController::class, 'index']);                  // Listar todos
Route::get('/clientes/buscar', [ClienteController::class, 'buscar']);          // Buscar por NIT/email
Route::get('/clientes/cercanos', [ClienteController::class, 'cercanos']);      // Buscar cercanos por GPS
Route::get('/clientes/{id}', [ClienteController::class, 'show']);              // Obtener uno
Route::post('/clientes', [ClienteController::class, 'store']);                 // Crear nuevo
Route::put('/clientes/{id}', [ClienteController::class, 'update']);            // Actualizar
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy']);        // Eliminar
Route::patch('/clientes/{id}/verificar', [ClienteController::class, 'verificar']); // Verificar

// Usuarios - Autenticación
Route::post('/usuarios/login', [UsuarioController::class, 'login']);                   // Login
Route::post('/usuarios/logout', [UsuarioController::class, 'logout']);                 // Logout
Route::post('/usuarios/refresh-token', [UsuarioController::class, 'refreshToken']);    // Refresh token
Route::get('/usuarios/verify-token', [UsuarioController::class, 'verifyToken']);       // Verify token
Route::post('/usuarios/forgot-password', [UsuarioController::class, 'forgotPassword']); // Forgot password
Route::post('/usuarios/reset-password', [UsuarioController::class, 'resetPassword']);   // Reset password
Route::patch('/usuarios/{id}/cambiar-password', [UsuarioController::class, 'cambiarPassword']); // Cambiar password

// Usuarios - Gestión
Route::get('/usuarios', [UsuarioController::class, 'index']);           // Listar todos
Route::get('/usuarios/buscar', [UsuarioController::class, 'buscar']);   // Buscar por email/DPI
Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);       // Obtener uno
Route::post('/usuarios', [UsuarioController::class, 'store']);          // Crear nuevo
Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);     // Actualizar
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']); // Desactivar (soft delete)
