<?php

namespace App\Http\Controllers\Api\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Usuarios\Usuario;
use App\Models\Core\Rol;
use App\Models\Core\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UsuarioController extends Controller
{
    /**
     * Get JWT secret from config/environment
     */
    private function getJWTSecret()
    {
        return env('JWT_SECRET', 'tu_clave_secreta_super_segura');
    }

    /**
     * Get JWT expiration time
     */
    private function getJWTExpiration()
    {
        return env('JWT_EXPIRES_IN', '8h');
    }

    // ==================== FUNCIONES DE AUTENTICACIÓN ====================

    /**
     * LOGIN - Autenticar usuario
     * POST /api/usuarios/login
     */
    public function login(Request $request)
    {
        try {
            // Validar que vengan los datos
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ], [
                'email.required' => 'Email es requerido',
                'password.required' => 'Contraseña es requerida'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email y contraseña son requeridos',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Buscar usuario por email
            $usuario = Usuario::with(['rol', 'sucursal'])
                ->where('email', $request->email)
                ->first();

            // Verificar si el usuario existe
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            // Verificar si el usuario está activo
            if (!$usuario->activo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario inactivo. Contacta al administrador'
                ], 403);
            }

            // Verificar contraseña
            if (!Hash::check($request->password, $usuario->password_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            // Generar JWT token
            $payload = [
                'iss' => env('APP_URL', 'http://localhost'),
                'iat' => time(),
                'exp' => time() + $this->convertTimeToSeconds($this->getJWTExpiration()),
                'id' => $usuario->id,
                'email' => $usuario->email,
                'rol' => $usuario->rol ? $usuario->rol->nombre : null,
                'rol_id' => $usuario->rol_id,
                'sucursal_id' => $usuario->sucursal_id
            ];

            $token = JWT::encode($payload, $this->getJWTSecret(), 'HS256');

            // Actualizar último acceso
            $usuario->update(['ultimo_acceso' => now()]);

            // Preparar datos del usuario (sin password)
            $userData = [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'dpi' => $usuario->dpi,
                'rol_id' => $usuario->rol_id,
                'rol' => $usuario->rol ? $usuario->rol->nombre : null,
                'sucursal' => $usuario->sucursal ? $usuario->sucursal->nombre : null,
                'sucursal_id' => $usuario->sucursal_id,
                'activo' => $usuario->activo
            ];

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'token' => $token,
                'user' => $userData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * LOGOUT - Cerrar sesión
     * POST /api/usuarios/logout
     */
    public function logout(Request $request)
    {
        try {
            // En JWT stateless, el logout se maneja en el frontend eliminando el token
            return response()->json([
                'success' => true,
                'message' => 'Logout exitoso'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * REFRESH TOKEN - Renovar token
     * POST /api/usuarios/refresh-token
     */
    public function refreshToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token requerido'
                ], 400);
            }

            // Verificar token actual
            try {
                $decoded = JWT::decode($request->token, new Key($this->getJWTSecret(), 'HS256'));
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido o expirado'
                ], 401);
            }

            // Verificar que el usuario todavía existe y está activo
            $usuario = Usuario::find($decoded->id);

            if (!$usuario || !$usuario->activo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado o inactivo'
                ], 401);
            }

            // Generar nuevo token
            $payload = [
                'iss' => env('APP_URL', 'http://localhost'),
                'iat' => time(),
                'exp' => time() + $this->convertTimeToSeconds($this->getJWTExpiration()),
                'id' => $decoded->id,
                'email' => $decoded->email,
                'rol_id' => $decoded->rol_id,
                'sucursal_id' => $decoded->sucursal_id
            ];

            $newToken = JWT::encode($payload, $this->getJWTSecret(), 'HS256');

            return response()->json([
                'success' => true,
                'message' => 'Token renovado exitosamente',
                'token' => $newToken
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al renovar token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * VERIFY TOKEN - Verificar si el token es válido
     * GET /api/usuarios/verify-token
     */
    public function verifyToken(Request $request)
    {
        try {
            // El token viene en el header Authorization
            $authHeader = $request->header('Authorization');

            if (!$authHeader) {
                return response()->json([
                    'success' => false,
                    'valid' => false,
                    'message' => 'Token no proporcionado'
                ], 401);
            }

            $token = str_replace('Bearer ', '', $authHeader);

            // Verificar token
            try {
                $decoded = JWT::decode($token, new Key($this->getJWTSecret(), 'HS256'));
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'valid' => false,
                    'message' => 'Token inválido o expirado'
                ], 401);
            }

            // Verificar que el usuario todavía existe y está activo
            $usuario = Usuario::with(['rol', 'sucursal'])->find($decoded->id);

            if (!$usuario || !$usuario->activo) {
                return response()->json([
                    'success' => false,
                    'valid' => false,
                    'message' => 'Usuario no encontrado o inactivo'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'valid' => true,
                'user' => [
                    'id' => $usuario->id,
                    'nombre' => $usuario->nombre,
                    'email' => $usuario->email,
                    'rol' => $usuario->rol ? $usuario->rol->nombre : null,
                    'sucursal' => $usuario->sucursal ? $usuario->sucursal->nombre : null
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => 'Error al verificar token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * CAMBIAR PASSWORD (autenticado)
     * PATCH /api/usuarios/{id}/cambiar-password
     */
    public function cambiarPassword(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'currentPassword' => 'required',
                'newPassword' => 'required|min:6'
            ], [
                'currentPassword.required' => 'Contraseña actual es requerida',
                'newPassword.required' => 'Nueva contraseña es requerida',
                'newPassword.min' => 'La nueva contraseña debe tener al menos 6 caracteres'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buscar usuario
            $usuario = Usuario::find($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // Verificar contraseña actual
            if (!Hash::check($request->currentPassword, $usuario->password_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contraseña actual incorrecta'
                ], 401);
            }

            // Actualizar contraseña
            $usuario->password_hash = Hash::make($request->newPassword);
            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar contraseña',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * FORGOT PASSWORD - Solicitar recuperación de contraseña
     * POST /api/usuarios/forgot-password
     */
    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email requerido'
                ], 400);
            }

            // Buscar usuario
            $usuario = Usuario::where('email', $request->email)->first();

            // Por seguridad, siempre devolvemos el mismo mensaje
            $mensaje = 'Si el email existe, recibirás instrucciones para recuperar tu contraseña';

            if (!$usuario) {
                return response()->json([
                    'success' => true,
                    'message' => $mensaje
                ], 200);
            }

            // Generar token de recuperación (válido por 1 hora)
            $payload = [
                'iss' => env('APP_URL', 'http://localhost'),
                'iat' => time(),
                'exp' => time() + 3600, // 1 hora
                'id' => $usuario->id,
                'email' => $usuario->email
            ];

            $resetToken = JWT::encode($payload, $this->getJWTSecret(), 'HS256');

            // Guardar token y fecha de expiración
            $usuario->update([
                'reset_token' => $resetToken,
                'reset_token_expira' => now()->addHour()
            ]);

            // TODO: Enviar email con el link de recuperación

            $response = [
                'success' => true,
                'message' => $mensaje
            ];

            // SOLO PARA DESARROLLO
            if (env('APP_ENV') === 'local') {
                $response['debug_token'] = $resetToken;
                $response['debug_link'] = env('FRONTEND_URL', 'http://localhost:3000') . '/reset-password?token=' . $resetToken;
            }

            return response()->json($response, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en forgot password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * RESET PASSWORD - Cambiar contraseña con token
     * POST /api/usuarios/reset-password
     */
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'newPassword' => 'required|min:6'
            ], [
                'token.required' => 'Token es requerido',
                'newPassword.required' => 'Nueva contraseña es requerida',
                'newPassword.min' => 'La contraseña debe tener al menos 6 caracteres'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar token
            try {
                $decoded = JWT::decode($request->token, new Key($this->getJWTSecret(), 'HS256'));
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido o expirado'
                ], 401);
            }

            // Buscar usuario con el token
            $usuario = Usuario::where('id', $decoded->id)
                ->where('reset_token', $request->token)
                ->first();

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido o expirado'
                ], 401);
            }

            // Verificar que el token no haya expirado
            if ($usuario->reset_token_expira && now()->gt($usuario->reset_token_expira)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token expirado'
                ], 401);
            }

            // Actualizar contraseña y limpiar token
            $usuario->update([
                'password_hash' => Hash::make($request->newPassword),
                'reset_token' => null,
                'reset_token_expira' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al resetear contraseña',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== FUNCIONES DE GESTIÓN DE USUARIOS ====================

    /**
     * Obtener todos los usuarios con filtros opcionales
     * GET /api/usuarios?activo=true&rol_id=1&sucursal_id=2
     */
    public function index(Request $request)
    {
        try {
            $query = Usuario::with(['rol', 'sucursal']);

            // Filtros opcionales
            if ($request->has('activo')) {
                $query->where('activo', $request->activo === 'true');
            }

            if ($request->has('rol_id')) {
                $query->where('rol_id', $request->rol_id);
            }

            if ($request->has('sucursal_id')) {
                $query->where('sucursal_id', $request->sucursal_id);
            }

            $usuarios = $query->orderBy('nombre', 'asc')->get();

            return response()->json([
                'success' => true,
                'count' => $usuarios->count(),
                'data' => $usuarios
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener usuario por ID
     * GET /api/usuarios/{id}
     */
    public function show($id)
    {
        try {
            $usuario = Usuario::with(['rol', 'sucursal'])->find($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $usuario
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar usuario por email o DPI
     * GET /api/usuarios/buscar?email=usuario@example.com&dpi=123456789
     */
    public function buscar(Request $request)
    {
        try {
            $email = $request->query('email');
            $dpi = $request->query('dpi');

            if (!$email && !$dpi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar email o DPI para buscar'
                ], 400);
            }

            $query = Usuario::with(['rol', 'sucursal']);

            if ($email) {
                $query->where('email', $email);
            }

            if ($dpi) {
                $query->where('dpi', $dpi);
            }

            $usuario = $query->first();

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $usuario
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nuevo usuario
     * POST /api/usuarios
     */
    public function store(Request $request)
    {
        try {
            // Validaciones
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:120',
                'dpi' => 'required|string|max:20|unique:usuarios,dpi',
                'email' => 'required|email|max:150|unique:usuarios,email',
                'password' => 'required|string|min:6',
                'rol_id' => 'required|integer|exists:roles,id',
                'sucursal_id' => 'nullable|integer|exists:sucursales,id',
                'activo' => 'nullable|boolean'
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'dpi.required' => 'El DPI es obligatorio',
                'dpi.unique' => 'Ya existe un usuario con ese DPI',
                'email.required' => 'El email es obligatorio',
                'email.unique' => 'Ya existe un usuario con ese email',
                'email.email' => 'Email inválido',
                'password.required' => 'La contraseña es obligatoria',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres',
                'rol_id.required' => 'El rol es obligatorio',
                'rol_id.exists' => 'El rol no existe',
                'sucursal_id.exists' => 'La sucursal no existe'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear usuario
            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'dpi' => $request->dpi,
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'rol_id' => $request->rol_id,
                'sucursal_id' => $request->sucursal_id,
                'activo' => $request->activo ?? true
            ]);

            // Cargar relaciones
            $usuario->load(['rol', 'sucursal']);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => $usuario
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar usuario
     * PUT /api/usuarios/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuario::find($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // Validaciones
            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:120',
                'dpi' => 'sometimes|required|string|max:20|unique:usuarios,dpi,' . $id,
                'email' => 'sometimes|required|email|max:150|unique:usuarios,email,' . $id,
                'password' => 'nullable|string|min:6',
                'rol_id' => 'sometimes|required|integer|exists:roles,id',
                'sucursal_id' => 'nullable|integer|exists:sucursales,id',
                'activo' => 'nullable|boolean'
            ], [
                'dpi.unique' => 'Ya existe un usuario con ese DPI',
                'email.unique' => 'Ya existe un usuario con ese email',
                'email.email' => 'Email inválido',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres',
                'rol_id.exists' => 'El rol no existe',
                'sucursal_id.exists' => 'La sucursal no existe'
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
                $usuario->nombre = $request->nombre;
            }
            if ($request->has('dpi')) {
                $usuario->dpi = $request->dpi;
            }
            if ($request->has('email')) {
                $usuario->email = $request->email;
            }
            if ($request->has('rol_id')) {
                $usuario->rol_id = $request->rol_id;
            }
            if ($request->has('sucursal_id')) {
                $usuario->sucursal_id = $request->sucursal_id;
            }
            if ($request->has('activo')) {
                $usuario->activo = $request->activo;
            }

            // Actualizar password si se proporciona
            if ($request->has('password') && $request->password) {
                $usuario->password_hash = Hash::make($request->password);
            }

            $usuario->save();
            $usuario->load(['rol', 'sucursal']);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente',
                'data' => $usuario
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar usuario (soft delete)
     * DELETE /api/usuarios/{id}
     */
    public function destroy($id)
    {
        try {
            $usuario = Usuario::find($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // Soft delete: marcar como inactivo
            $usuario->activo = false;
            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Usuario desactivado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desactivar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== HELPER FUNCTIONS ====================

    /**
     * Convertir tiempo a segundos (ej: "8h" => 28800)
     */
    private function convertTimeToSeconds($time)
    {
        $unit = substr($time, -1);
        $value = intval(substr($time, 0, -1));

        switch ($unit) {
            case 's':
                return $value;
            case 'm':
                return $value * 60;
            case 'h':
                return $value * 3600;
            case 'd':
                return $value * 86400;
            default:
                return 28800; // 8 horas por defecto
        }
    }
}
