<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Presentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PresentacionController extends Controller
{
    /**
     * Listar todas las presentaciones activas
     */
    public function index()
    {
        try {
            $presentaciones = Presentacion::where('activo', true)
                ->orderBy('nombre', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'count' => $presentaciones->count(),
                'data' => $presentaciones
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener presentaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar todas las presentaciones (incluyendo inactivas)
     */
    public function all()
    {
        try {
            $presentaciones = Presentacion::orderBy('nombre', 'asc')->get();

            return response()->json([
                'success' => true,
                'count' => $presentaciones->count(),
                'data' => $presentaciones
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener presentaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una presentación por ID
     */
    public function show($id)
    {
        try {
            $presentacion = Presentacion::find($id);

            if (!$presentacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presentación no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $presentacion
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la presentación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva presentación
     */
    public function store(Request $request)
    {
        try {
            // Validación
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:40|unique:presentaciones,nombre',
                'unidad_base' => 'nullable|string|max:20',
                'factor_galon' => 'nullable|numeric|min:0',
                'activo' => 'nullable|boolean'
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.unique' => 'Ya existe una presentación con ese nombre',
                'factor_galon.numeric' => 'El factor de galón debe ser un número',
                'factor_galon.min' => 'El factor de galón debe ser mayor o igual a 0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $presentacion = Presentacion::create([
                'nombre' => $request->nombre,
                'unidad_base' => $request->unidad_base,
                'factor_galon' => $request->factor_galon,
                'activo' => $request->activo ?? true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Presentación creada exitosamente',
                'data' => $presentacion
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la presentación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una presentación
     */
    public function update(Request $request, $id)
    {
        try {
            $presentacion = Presentacion::find($id);

            if (!$presentacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presentación no encontrada'
                ], 404);
            }

            // Validación
            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:40|unique:presentaciones,nombre,' . $id,
                'unidad_base' => 'nullable|string|max:20',
                'factor_galon' => 'nullable|numeric|min:0',
                'activo' => 'nullable|boolean'
            ], [
                'nombre.unique' => 'Ya existe una presentación con ese nombre',
                'factor_galon.numeric' => 'El factor de galón debe ser un número',
                'factor_galon.min' => 'El factor de galón debe ser mayor o igual a 0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar solo los campos proporcionados
            if ($request->has('nombre')) {
                $presentacion->nombre = $request->nombre;
            }
            if ($request->has('unidad_base')) {
                $presentacion->unidad_base = $request->unidad_base;
            }
            if ($request->has('factor_galon')) {
                $presentacion->factor_galon = $request->factor_galon;
            }
            if ($request->has('activo')) {
                $presentacion->activo = $request->activo;
            }

            $presentacion->save();

            return response()->json([
                'success' => true,
                'message' => 'Presentación actualizada exitosamente',
                'data' => $presentacion
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la presentación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar (soft delete) una presentación
     */
    public function destroy($id)
    {
        try {
            $presentacion = Presentacion::find($id);

            if (!$presentacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presentación no encontrada'
                ], 404);
            }

            // Soft delete: marcar como inactivo
            $presentacion->activo = false;
            $presentacion->save();

            return response()->json([
                'success' => true,
                'message' => 'Presentación desactivada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desactivar la presentación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reactivar una presentación
     */
    public function reactivar($id)
    {
        try {
            $presentacion = Presentacion::find($id);

            if (!$presentacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presentación no encontrada'
                ], 404);
            }

            $presentacion->activo = true;
            $presentacion->save();

            return response()->json([
                'success' => true,
                'message' => 'Presentación reactivada exitosamente',
                'data' => $presentacion
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reactivar la presentación',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
