<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarcaController extends Controller
{
    /**
     * Listar todas las marcas
     */
    public function index()
    {
        $marcas = Marca::all();
        return response()->json([
            'success' => true,
            'data' => $marcas
        ], 200);
    }

    /**
     * Crear una nueva marca
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:marcas,nombre',
            'activa' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $marca = Marca::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Marca creada exitosamente',
            'data' => $marca
        ], 201);
    }

    /**
     * Mostrar una marca específica
     */
    public function show($id)
    {
        $marca = Marca::find($id);

        if (!$marca) {
            return response()->json([
                'success' => false,
                'message' => 'Marca no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $marca
        ], 200);
    }

    /**
     * Actualizar una marca existente
     */
    public function update(Request $request, $id)
    {
        $marca = Marca::find($id);

        if (!$marca) {
            return response()->json([
                'success' => false,
                'message' => 'Marca no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'string|max:255|unique:marcas,nombre,' . $id,
            'activa' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $marca->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Marca actualizada exitosamente',
            'data' => $marca
        ], 200);
    }

    /**
     * Eliminar una marca
     */
    public function destroy($id)
    {
        $marca = Marca::find($id);

        if (!$marca) {
            return response()->json([
                'success' => false,
                'message' => 'Marca no encontrada'
            ], 404);
        }

        // Verificar si tiene productos asociados
        if ($marca->productos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la marca porque tiene productos asociados'
            ], 409);
        }

        $marca->delete();

        return response()->json([
            'success' => true,
            'message' => 'Marca eliminada exitosamente'
        ], 200);
    }
}
