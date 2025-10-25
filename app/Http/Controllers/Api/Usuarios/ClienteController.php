<?php

namespace App\Http\Controllers\Api\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Usuarios\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Obtener todos los clientes con filtros opcionales
     * GET /api/clientes?verificado=true&opt_in_promos=true
     */
    public function index(Request $request)
    {
        try {
            $query = Cliente::query();

            // Filtrar por verificado
            if ($request->has('verificado')) {
                $query->where('verificado', $request->verificado === 'true');
            }

            // Filtrar por opt_in_promos
            if ($request->has('opt_in_promos')) {
                $query->where('opt_in_promos', $request->opt_in_promos === 'true');
            }

            $clientes = $query->orderBy('nombre', 'asc')->get();

            return response()->json([
                'success' => true,
                'count' => $clientes->count(),
                'data' => $clientes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener clientes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener cliente por ID
     * GET /api/clientes/{id}
     */
    public function show($id)
    {
        try {
            $cliente = Cliente::find($id);

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $cliente
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar cliente por NIT o email
     * GET /api/clientes/buscar?nit=123456&email=cliente@example.com
     */
    public function buscar(Request $request)
    {
        try {
            $nit = $request->query('nit');
            $email = $request->query('email');

            if (!$nit && !$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar NIT o email para buscar'
                ], 400);
            }

            $query = Cliente::query();

            if ($nit) {
                $query->where('nit', $nit);
            }

            if ($email) {
                $query->where('email', $email);
            }

            $cliente = $query->first();

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $cliente
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar clientes cercanos por GPS
     * GET /api/clientes/cercanos?lat=14.634915&lng=-90.506882&radio=10
     */
    public function cercanos(Request $request)
    {
        try {
            $lat = $request->query('lat');
            $lng = $request->query('lng');
            $radio = $request->query('radio', 10); // Radio por defecto 10 km

            if (!$lat || !$lng) {
                return response()->json([
                    'success' => false,
                    'message' => 'Se requieren las coordenadas (lat, lng)'
                ], 400);
            }

            $latitude = floatval($lat);
            $longitude = floatval($lng);
            $radioKm = floatval($radio);

            // Fórmula de Haversine para calcular distancia
            $clientes = Cliente::select('*')
                ->selectRaw("
                    6371 * acos(
                        cos(radians(?))
                        * cos(radians(gps_lat))
                        * cos(radians(gps_lng) - radians(?))
                        + sin(radians(?))
                        * sin(radians(gps_lat))
                    ) AS distancia
                ", [$latitude, $longitude, $latitude])
                ->whereNotNull('gps_lat')
                ->whereNotNull('gps_lng')
                ->having('distancia', '<=', $radioKm)
                ->orderBy('distancia', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'count' => $clientes->count(),
                'radio_km' => $radioKm,
                'data' => $clientes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar clientes cercanos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nuevo cliente
     * POST /api/clientes
     */
    public function store(Request $request)
    {
        try {
            // Validación
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:150',
                'nit' => 'nullable|string|max:25|unique:clientes,nit',
                'email' => 'nullable|email|max:150|unique:clientes,email',
                'password' => 'nullable|string|min:6',
                'opt_in_promos' => 'nullable|boolean',
                'telefono' => 'nullable|string|max:30',
                'direccion' => 'nullable|string|max:255',
                'gps_lat' => 'nullable|numeric|min:-90|max:90',
                'gps_lng' => 'nullable|numeric|min:-180|max:180'
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'nit.unique' => 'Ya existe un cliente con ese NIT',
                'email.unique' => 'Ya existe un cliente con ese email',
                'email.email' => 'Email inválido',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres',
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

            // Preparar datos
            $data = [
                'nombre' => $request->nombre,
                'nit' => $request->nit,
                'email' => $request->email,
                'opt_in_promos' => $request->opt_in_promos ?? false,
                'verificado' => false,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'gps_lat' => $request->gps_lat,
                'gps_lng' => $request->gps_lng
            ];

            // Hashear password si se proporciona
            if ($request->password) {
                $data['password_hash'] = Hash::make($request->password);
            }

            $cliente = Cliente::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Cliente creado exitosamente',
                'data' => $cliente
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar cliente
     * PUT /api/clientes/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $cliente = Cliente::find($id);

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            // Validación
            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:150',
                'nit' => 'nullable|string|max:25|unique:clientes,nit,' . $id,
                'email' => 'nullable|email|max:150|unique:clientes,email,' . $id,
                'password' => 'nullable|string|min:6',
                'opt_in_promos' => 'nullable|boolean',
                'telefono' => 'nullable|string|max:30',
                'direccion' => 'nullable|string|max:255',
                'gps_lat' => 'nullable|numeric|min:-90|max:90',
                'gps_lng' => 'nullable|numeric|min:-180|max:180'
            ], [
                'nit.unique' => 'Ya existe un cliente con ese NIT',
                'email.unique' => 'Ya existe un cliente con ese email',
                'email.email' => 'Email inválido',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres',
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
                $cliente->nombre = $request->nombre;
            }
            if ($request->has('nit')) {
                $cliente->nit = $request->nit;
            }
            if ($request->has('email')) {
                $cliente->email = $request->email;
            }
            if ($request->has('opt_in_promos')) {
                $cliente->opt_in_promos = $request->opt_in_promos;
            }
            if ($request->has('telefono')) {
                $cliente->telefono = $request->telefono;
            }
            if ($request->has('direccion')) {
                $cliente->direccion = $request->direccion;
            }
            if ($request->has('gps_lat')) {
                $cliente->gps_lat = $request->gps_lat;
            }
            if ($request->has('gps_lng')) {
                $cliente->gps_lng = $request->gps_lng;
            }

            // Actualizar password si se proporciona
            if ($request->has('password') && $request->password) {
                $cliente->password_hash = Hash::make($request->password);
            }

            $cliente->save();

            return response()->json([
                'success' => true,
                'message' => 'Cliente actualizado exitosamente',
                'data' => $cliente
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar cliente
     * DELETE /api/clientes/{id}
     */
    public function destroy($id)
    {
        try {
            $cliente = Cliente::find($id);

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            // TODO: Verificar si el cliente tiene facturas
            // (implementar después cuando tengamos el modelo Factura)

            $cliente->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cliente eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar cliente (marcar como verificado)
     * PATCH /api/clientes/{id}/verificar
     */
    public function verificar($id)
    {
        try {
            $cliente = Cliente::find($id);

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            $cliente->verificado = true;
            $cliente->save();

            return response()->json([
                'success' => true,
                'message' => 'Cliente verificado exitosamente',
                'data' => $cliente
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
