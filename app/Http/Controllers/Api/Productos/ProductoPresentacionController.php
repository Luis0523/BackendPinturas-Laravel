<?php

namespace App\Http\Controllers\Api\Productos;

use App\Http\Controllers\Controller;
use App\Models\Productos\ProductoPresentacion;
use App\Models\Productos\Producto;
use App\Models\Core\Presentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductoPresentacionController extends Controller
{
    /**
     * Listar todas las combinaciones activas de producto-presentación
     */
    public function index()
    {
        try {
            $productoPresentaciones = ProductoPresentacion::activos()
                ->with(['producto.marca', 'producto.categoria', 'presentacion'])
                ->orderBy('producto_id', 'asc')
                ->orderBy('presentacion_id', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'count' => $productoPresentaciones->count(),
                'data' => $productoPresentaciones
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las combinaciones producto-presentación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar todas las combinaciones (incluyendo inactivas)
     */
    public function all()
    {
        try {
            $productoPresentaciones = ProductoPresentacion::with(['producto.marca', 'producto.categoria', 'presentacion'])
                ->orderBy('producto_id', 'asc')
                ->orderBy('presentacion_id', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'count' => $productoPresentaciones->count(),
                'data' => $productoPresentaciones
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las combinaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener catálogo completo vendible
     * Devuelve todas las combinaciones activas listas para vender
     */
    public function catalogo()
    {
        try {
            $catalogo = ProductoPresentacion::catalogo()
                ->whereHas('producto', function ($query) {
                    $query->where('activo', true);
                })
                ->whereHas('presentacion', function ($query) {
                    $query->where('activo', true);
                })
                ->orderBy('producto_id', 'asc')
                ->orderBy('presentacion_id', 'asc')
                ->get();

            // Transformar datos para catálogo más legible
            $catalogoFormateado = $catalogo->map(function ($item) {
                return [
                    'id' => $item->id,
                    'producto_presentacion_id' => $item->id,
                    'producto_id' => $item->producto_id,
                    'producto' => [
                        'codigo_sku' => $item->producto->codigo_sku,
                        'descripcion' => $item->producto->descripcion,
                        'marca' => $item->producto->marca->nombre ?? null,
                        'categoria' => $item->producto->categoria->nombre ?? null,
                        'color' => $item->producto->color,
                        'tamano' => $item->producto->tamano,
                    ],
                    'presentacion_id' => $item->presentacion_id,
                    'presentacion' => [
                        'nombre' => $item->presentacion->nombre,
                        'unidad_base' => $item->presentacion->unidad_base,
                        'factor_galon' => $item->presentacion->factor_galon,
                    ],
                    'activo' => $item->activo,
                ];
            });

            return response()->json([
                'success' => true,
                'count' => $catalogoFormateado->count(),
                'data' => $catalogoFormateado
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el catálogo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener presentaciones disponibles para un producto específico
     * GET /api/producto-presentacion/producto/{productoId}
     */
    public function porProducto($productoId)
    {
        try {
            // Verificar que el producto existe
            $producto = Producto::find($productoId);
            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $presentaciones = ProductoPresentacion::porProducto($productoId)
                ->activos()
                ->with('presentacion')
                ->orderBy('presentacion_id', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'producto_id' => $productoId,
                'producto' => [
                    'codigo_sku' => $producto->codigo_sku,
                    'descripcion' => $producto->descripcion,
                ],
                'count' => $presentaciones->count(),
                'presentaciones' => $presentaciones->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'presentacion_id' => $item->presentacion_id,
                        'nombre' => $item->presentacion->nombre,
                        'unidad_base' => $item->presentacion->unidad_base,
                        'factor_galon' => $item->presentacion->factor_galon,
                        'activo' => $item->activo,
                    ];
                })
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener presentaciones del producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una combinación específica por ID
     */
    public function show($id)
    {
        try {
            $productoPresentacion = ProductoPresentacion::with(['producto.marca', 'producto.categoria', 'presentacion'])
                ->find($id);

            if (!$productoPresentacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Combinación producto-presentación no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $productoPresentacion
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la combinación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva combinación producto-presentación
     */
    public function store(Request $request)
    {
        try {
            // Validación
            $validator = Validator::make($request->all(), [
                'producto_id' => 'required|integer|exists:productos,id',
                'presentacion_id' => 'required|integer|exists:presentaciones,id',
                'activo' => 'nullable|boolean'
            ], [
                'producto_id.required' => 'El ID del producto es obligatorio',
                'producto_id.exists' => 'El producto no existe',
                'presentacion_id.required' => 'El ID de la presentación es obligatorio',
                'presentacion_id.exists' => 'La presentación no existe'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar si ya existe la combinación (incluso si está inactiva)
            $existente = ProductoPresentacion::where('producto_id', $request->producto_id)
                ->where('presentacion_id', $request->presentacion_id)
                ->first();

            if ($existente) {
                if (!$existente->activo) {
                    // Si existe pero está inactiva, reactivarla
                    $existente->activo = true;
                    $existente->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Combinación reactivada exitosamente',
                        'data' => $existente->load(['producto', 'presentacion'])
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Esta combinación producto-presentación ya existe'
                    ], 409); // 409 Conflict
                }
            }

            // Crear nueva combinación
            $productoPresentacion = ProductoPresentacion::create([
                'producto_id' => $request->producto_id,
                'presentacion_id' => $request->presentacion_id,
                'activo' => $request->activo ?? true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Combinación producto-presentación creada exitosamente',
                'data' => $productoPresentacion->load(['producto', 'presentacion'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la combinación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Agregar múltiples presentaciones a un producto en batch
     * POST /api/producto-presentacion/batch
     * Body: { "producto_id": 1, "presentacion_ids": [1, 2, 3] }
     */
    public function storeBatch(Request $request)
    {
        try {
            // Validación
            $validator = Validator::make($request->all(), [
                'producto_id' => 'required|integer|exists:productos,id',
                'presentacion_ids' => 'required|array|min:1',
                'presentacion_ids.*' => 'required|integer|exists:presentaciones,id'
            ], [
                'producto_id.required' => 'El ID del producto es obligatorio',
                'producto_id.exists' => 'El producto no existe',
                'presentacion_ids.required' => 'Debe proporcionar al menos una presentación',
                'presentacion_ids.array' => 'Las presentaciones deben ser un array',
                'presentacion_ids.*.exists' => 'Una o más presentaciones no existen'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $productoId = $request->producto_id;
            $presentacionIds = $request->presentacion_ids;

            $creadas = 0;
            $reactivadas = 0;
            $duplicadas = 0;
            $resultados = [];

            DB::beginTransaction();

            foreach ($presentacionIds as $presentacionId) {
                // Verificar si ya existe
                $existente = ProductoPresentacion::where('producto_id', $productoId)
                    ->where('presentacion_id', $presentacionId)
                    ->first();

                if ($existente) {
                    if (!$existente->activo) {
                        // Reactivar
                        $existente->activo = true;
                        $existente->save();
                        $reactivadas++;
                        $resultados[] = [
                            'producto_id' => $productoId,
                            'presentacion_id' => $presentacionId,
                            'accion' => 'reactivada',
                            'id' => $existente->id
                        ];
                    } else {
                        // Ya existe y está activa
                        $duplicadas++;
                        $resultados[] = [
                            'producto_id' => $productoId,
                            'presentacion_id' => $presentacionId,
                            'accion' => 'ya_existe',
                            'id' => $existente->id
                        ];
                    }
                } else {
                    // Crear nueva
                    $nueva = ProductoPresentacion::create([
                        'producto_id' => $productoId,
                        'presentacion_id' => $presentacionId,
                        'activo' => true
                    ]);
                    $creadas++;
                    $resultados[] = [
                        'producto_id' => $productoId,
                        'presentacion_id' => $presentacionId,
                        'accion' => 'creada',
                        'id' => $nueva->id
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proceso batch completado',
                'resumen' => [
                    'total_procesadas' => count($presentacionIds),
                    'creadas' => $creadas,
                    'reactivadas' => $reactivadas,
                    'duplicadas' => $duplicadas
                ],
                'resultados' => $resultados
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el batch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una combinación
     */
    public function update(Request $request, $id)
    {
        try {
            $productoPresentacion = ProductoPresentacion::find($id);

            if (!$productoPresentacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Combinación no encontrada'
                ], 404);
            }

            // Validación
            $validator = Validator::make($request->all(), [
                'producto_id' => 'sometimes|required|integer|exists:productos,id',
                'presentacion_id' => 'sometimes|required|integer|exists:presentaciones,id',
                'activo' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Si se intenta cambiar producto_id o presentacion_id, verificar unicidad
            if ($request->has('producto_id') || $request->has('presentacion_id')) {
                $nuevoProductoId = $request->producto_id ?? $productoPresentacion->producto_id;
                $nuevaPresentacionId = $request->presentacion_id ?? $productoPresentacion->presentacion_id;

                $existente = ProductoPresentacion::where('producto_id', $nuevoProductoId)
                    ->where('presentacion_id', $nuevaPresentacionId)
                    ->where('id', '!=', $id)
                    ->first();

                if ($existente) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ya existe una combinación con esos valores'
                    ], 409);
                }
            }

            // Actualizar
            if ($request->has('producto_id')) {
                $productoPresentacion->producto_id = $request->producto_id;
            }
            if ($request->has('presentacion_id')) {
                $productoPresentacion->presentacion_id = $request->presentacion_id;
            }
            if ($request->has('activo')) {
                $productoPresentacion->activo = $request->activo;
            }

            $productoPresentacion->save();

            return response()->json([
                'success' => true,
                'message' => 'Combinación actualizada exitosamente',
                'data' => $productoPresentacion->load(['producto', 'presentacion'])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la combinación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar (soft delete) una combinación
     */
    public function destroy($id)
    {
        try {
            $productoPresentacion = ProductoPresentacion::find($id);

            if (!$productoPresentacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Combinación no encontrada'
                ], 404);
            }

            // Soft delete: marcar como inactivo
            $productoPresentacion->activo = false;
            $productoPresentacion->save();

            return response()->json([
                'success' => true,
                'message' => 'Combinación desactivada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desactivar la combinación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reactivar una combinación
     */
    public function reactivar($id)
    {
        try {
            $productoPresentacion = ProductoPresentacion::find($id);

            if (!$productoPresentacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Combinación no encontrada'
                ], 404);
            }

            $productoPresentacion->activo = true;
            $productoPresentacion->save();

            return response()->json([
                'success' => true,
                'message' => 'Combinación reactivada exitosamente',
                'data' => $productoPresentacion->load(['producto', 'presentacion'])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reactivar la combinación',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
