<?php

namespace App\Http\Controllers\Api\Productos;

use App\Http\Controllers\Controller;
use App\Models\Productos\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Listar todos los productos con sus relaciones
     */
    public function index()
    {
        $productos = Producto::with(['marca', 'categoria'])->get();
        return response()->json([
            'success' => true,
            'data' => $productos
        ], 200);
    }

    /**
     * Crear un nuevo producto
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'codigo_sku' => 'required|string|max:255|unique:productos,codigo_sku',
            'descripcion' => 'required|string',
            'tamano' => 'nullable|string',
            'duracion_anios' => 'nullable|integer',
            'extension_m2' => 'nullable|numeric',
            'color' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $producto = Producto::create($request->all());
        $producto->load(['marca', 'categoria']);

        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente',
            'data' => $producto
        ], 201);
    }

    /**
     * Mostrar un producto específico con sus relaciones
     */
    public function show($id)
    {
        $producto = Producto::with(['marca', 'categoria'])->find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $producto
        ], 200);
    }

    /**
     * Actualizar un producto existente
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'categoria_id' => 'exists:categorias,id',
            'marca_id' => 'exists:marcas,id',
            'codigo_sku' => 'string|max:255|unique:productos,codigo_sku,' . $id,
            'descripcion' => 'string',
            'tamano' => 'nullable|string',
            'duracion_anios' => 'nullable|integer',
            'extension_m2' => 'nullable|numeric',
            'color' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $producto->update($request->all());
        $producto->load(['marca', 'categoria']);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado exitosamente',
            'data' => $producto
        ], 200);
    }

    /**
     * Eliminar un producto
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $producto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado exitosamente'
        ], 200);
    }
}
