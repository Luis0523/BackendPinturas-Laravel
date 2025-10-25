<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'clientes';

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
        'nit',
        'email',
        'password_hash',
        'opt_in_promos',
        'verificado',
        'telefono',
        'direccion',
        'gps_lat',
        'gps_lng'
    ];

    /**
     * Atributos ocultos (nunca se exponen en JSON)
     */
    protected $hidden = [
        'password_hash'
    ];

    /**
     * Conversión de tipos (casting)
     */
    protected $casts = [
        'opt_in_promos' => 'boolean',
        'verificado' => 'boolean',
        'gps_lat' => 'decimal:6',
        'gps_lng' => 'decimal:6'
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'opt_in_promos' => false,
        'verificado' => false
    ];

    /**
     * Scope para obtener clientes verificados
     */
    public function scopeVerificados($query)
    {
        return $query->where('verificado', true);
    }

    /**
     * Scope para obtener clientes con opt-in de promos
     */
    public function scopeConPromos($query)
    {
        return $query->where('opt_in_promos', true);
    }

    /**
     * Scope para buscar por NIT
     */
    public function scopePorNit($query, $nit)
    {
        return $query->where('nit', $nit);
    }

    /**
     * Scope para buscar por email
     */
    public function scopePorEmail($query, $email)
    {
        return $query->where('email', $email);
    }
}
