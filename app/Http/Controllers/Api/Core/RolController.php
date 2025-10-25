<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolController extends Controller
{
    /**
     * Listar todos los roles
     */
    public function index()
    {
        try {
            $roles = Rol::orderBy('nombre', 'asc')->get();

            return response()->json([
                'success' => true,
                'count' => $roles->count(),
                'data' => $roles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un rol por ID
     */
    public function show($id)
    {
        try {
            $rol = Rol::find($id);

            if (!$rol) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rol no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $rol
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo rol
     */
    public function store(Request $request)
    {
        try {
            // Validación
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:30|unique:roles,nombre'
            ], [
                'nombre.required' => 'El nombre del rol es obligatorio',
                'nombre.unique' => 'Ya existe un rol con ese nombre'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $rol = Rol::create([
                'nombre' => $request->nombre
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rol creado exitosamente',
                'data' => $rol
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un rol
     */
    public function update(Request $request, $id)
    {
        try {
            $rol = Rol::find($id);

            if (!$rol) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rol no encontrado'
                ], 404);
            }

            // Validación
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:30|unique:roles,nombre,' . $id
            ], [
                'nombre.required' => 'El nombre del rol es obligatorio',
                'nombre.unique' => 'Ya existe un rol con ese nombre'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $rol->nombre = $request->nombre;
            $rol->save();

            return response()->json([
                'success' => true,
                'message' => 'Rol actualizado exitosamente',
                'data' => $rol
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un rol
     * Verifica que no haya usuarios asignados
     */
    public function destroy($id)
    {
        try {
            $rol = Rol::find($id);

            if (!$rol) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rol no encontrado'
                ], 404);
            }

            // Verificar si hay usuarios con este rol
            $usuariosCount = $rol->usuarios()->count();

            if ($usuariosCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "No se puede eliminar el rol. Hay {$usuariosCount} usuario(s) asignado(s) a este rol"
                ], 409);
            }

            $rol->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rol eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
