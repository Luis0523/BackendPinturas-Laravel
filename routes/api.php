<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Core\MarcaController;
use App\Http\Controllers\Api\Core\CategoriaController;
use App\Http\Controllers\Api\Productos\ProductoController;

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

// ==================== RUTAS PRODUCTOS ====================
// Productos
Route::get('/productos', [ProductoController::class, 'index']);           // Listar todos
Route::get('/productos/{id}', [ProductoController::class, 'show']);       // Obtener uno
Route::post('/productos', [ProductoController::class, 'store']);          // Crear nuevo
Route::put('/productos/{id}', [ProductoController::class, 'update']);     // Actualizar
Route::delete('/productos/{id}', [ProductoController::class, 'destroy']); // Eliminar
