<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Rol;
use App\Models\Core\Sucursal;

class Usuario extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'usuarios';

    /**
     * Indicar que solo usamos created_at personalizado
     */
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null; // No hay updatedAt

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'nombre',
        'dpi',
        'email',
        'password_hash',
        'rol_id',
        'sucursal_id',
        'activo',
        'ultimo_acceso',
        'reset_token',
        'reset_token_expira'
    ];

    /**
     * Atributos ocultos (nunca se exponen en JSON)
     */
    protected $hidden = [
        'password_hash',
        'reset_token'
    ];

    /**
     * Conversión de tipos (casting)
     */
    protected $casts = [
        'activo' => 'boolean',
        'rol_id' => 'integer',
        'sucursal_id' => 'integer',
        'ultimo_acceso' => 'datetime',
        'reset_token_expira' => 'datetime'
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'activo' => true
    ];

    /**
     * Relación: Un usuario pertenece a un rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Relación: Un usuario pertenece a una sucursal (opcional)
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    /**
     * Scope para obtener solo usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por rol
     */
    public function scopePorRol($query, $rolId)
    {
        return $query->where('rol_id', $rolId);
    }

    /**
     * Scope para filtrar por sucursal
     */
    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }

    /**
     * Scope para buscar por email
     */
    public function scopePorEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Scope para buscar por DPI
     */
    public function scopePorDpi($query, $dpi)
    {
        return $query->where('dpi', $dpi);
    }
}
