<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SucursalController extends Controller
{
    /**
     * Listar todas las sucursales activas
     */
    public function index()
    {
        try {
            $sucursales = Sucursal::where('activa', true)
                ->orderBy('nombre', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'count' => $sucursales->count(),
                'data' => $sucursales
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener sucursales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar todas las sucursales (incluyendo inactivas)
     */
    public function all()
    {
        try {
            $sucursales = Sucursal::orderBy('nombre', 'asc')->get();

            return response()->json([
                'success' => true,
                'count' => $sucursales->count(),
                'data' => $sucursales
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener sucursales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una sucursal por ID
     */
    public function show($id)
    {
        try {
            $sucursal = Sucursal::find($id);

            if (!$sucursal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sucursal no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $sucursal
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la sucursal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva sucursal
     */
    public function store(Request $request)
    {
        try {
            // Validación
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:120|unique:sucursales,nombre',
                'direccion' => 'nullable|string|max:255',
                'gps_lat' => 'nullable|numeric|min:-90|max:90',
                'gps_lng' => 'nullable|numeric|min:-180|max:180',
                'telefono' => 'nullable|string|max:30',
                'activa' => 'nullable|boolean'
            ], [
                'nombre.required' => 'El nombre de la sucursal es obligatorio',
                'nombre.unique' => 'Ya existe una sucursal con ese nombre',
                'gps_lat.min' => 'Latitud inválida. Debe estar entre -90 y 90',
                'gps_lat.max' => 'Latitud inválida. Debe estar entre -90 y 90',
                'gps_lng.min' => 'Longitud inválida. Debe estar entre -180 y 180',
                'gps_lng.max' => 'Longitud inválida. Debe estar entre -180 y 180'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $sucursal = Sucursal::create([
                'nombre' => $request->nombre,
                'direccion' => $request->direccion,
                'gps_lat' => $request->gps_lat,
                'gps_lng' => $request->gps_lng,
                'telefono' => $request->telefono,
                'activa' => $request->activa ?? true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sucursal creada exitosamente',
                'data' => $sucursal
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la sucursal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una sucursal
     */
    public function update(Request $request, $id)
    {
        try {
            $sucursal = Sucursal::find($id);

            if (!$sucursal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sucursal no encontrada'
                ], 404);
            }

            // Validación
            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:120|unique:sucursales,nombre,' . $id,
                'direccion' => 'nullable|string|max:255',
                'gps_lat' => 'nullable|numeric|min:-90|max:90',
                'gps_lng' => 'nullable|numeric|min:-180|max:180',
                'telefono' => 'nullable|string|max:30',
                'activa' => 'nullable|boolean'
            ], [
                'nombre.unique' => 'Ya existe una sucursal con ese nombre',
                'gps_lat.min' => 'Latitud inválida. Debe estar entre -90 y 90',
                'gps_lat.max' => 'Latitud inválida. Debe estar entre -90 y 90',
                'gps_lng.min' => 'Longitud inválida. Debe estar entre -180 y 180',
                'gps_lng.max' => 'Longitud inválida. Debe estar entre -180 y 180'
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
                $sucursal->nombre = $request->nombre;
            }
            if ($request->has('direccion')) {
                $sucursal->direccion = $request->direccion;
            }
            if ($request->has('gps_lat')) {
                $sucursal->gps_lat = $request->gps_lat;
            }
            if ($request->has('gps_lng')) {
                $sucursal->gps_lng = $request->gps_lng;
            }
            if ($request->has('telefono')) {
                $sucursal->telefono = $request->telefono;
            }
            if ($request->has('activa')) {
                $sucursal->activa = $request->activa;
            }

            $sucursal->save();

            return response()->json([
                'success' => true,
                'message' => 'Sucursal actualizada exitosamente',
                'data' => $sucursal
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la sucursal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar (soft delete) una sucursal
     */
    public function destroy($id)
    {
        try {
            $sucursal = Sucursal::find($id);

            if (!$sucursal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sucursal no encontrada'
                ], 404);
            }

            // Soft delete: marcar como inactiva
            $sucursal->activa = false;
            $sucursal->save();

            return response()->json([
                'success' => true,
                'message' => 'Sucursal desactivada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desactivar la sucursal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reactivar una sucursal
     */
    public function reactivar($id)
    {
        try {
            $sucursal = Sucursal::find($id);

            if (!$sucursal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sucursal no encontrada'
                ], 404);
            }

            $sucursal->activa = true;
            $sucursal->save();

            return response()->json([
                'success' => true,
                'message' => 'Sucursal reactivada exitosamente',
                'data' => $sucursal
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reactivar la sucursal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar sucursales cercanas por GPS (Fórmula de Haversine)
     */
    public function cercanas(Request $request)
    {
        try {
            // Validación
            $validator = Validator::make($request->all(), [
                'lat' => 'required|numeric|min:-90|max:90',
                'lng' => 'required|numeric|min:-180|max:180',
                'radio' => 'nullable|numeric|min:0'
            ], [
                'lat.required' => 'La latitud es obligatoria',
                'lng.required' => 'La longitud es obligatoria',
                'lat.min' => 'Latitud inválida',
                'lat.max' => 'Latitud inválida',
                'lng.min' => 'Longitud inválida',
                'lng.max' => 'Longitud inválida'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $lat = $request->lat;
            $lng = $request->lng;
            $radio = $request->radio ?? 10; // Radio por defecto: 10 km

            // Fórmula de Haversine para calcular distancia
            $sucursales = Sucursal::select([
                'id',
                'nombre',
                'direccion',
                'gps_lat',
                'gps_lng',
                'telefono',
                'activa',
                DB::raw("
                    (6371 * acos(
                        cos(radians($lat))
                        * cos(radians(gps_lat))
                        * cos(radians(gps_lng) - radians($lng))
                        + sin(radians($lat))
                        * sin(radians(gps_lat))
                    )) AS distancia
                ")
            ])
            ->where('activa', true)
            ->whereNotNull('gps_lat')
            ->whereNotNull('gps_lng')
            ->having('distancia', '<=', $radio)
            ->orderBy('distancia', 'asc')
            ->get();

            return response()->json([
                'success' => true,
                'count' => $sucursales->count(),
                'radio_km' => $radio,
                'data' => $sucursales
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar sucursales cercanas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
