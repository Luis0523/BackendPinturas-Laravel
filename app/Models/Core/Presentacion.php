<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'presentaciones';

    /**
     * Indicar que no usamos timestamps
     */
    public $timestamps = false;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'nombre',
        'unidad_base',
        'factor_galon',
        'activo'
    ];

    /**
     * Conversión de tipos (casting)
     */
    protected $casts = [
        'factor_galon' => 'decimal:5',
        'activo' => 'boolean'
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'activo' => true
    ];

    /**
     * Relación: Una presentación puede estar en muchos productos
     * (a través de la tabla pivote productopresentacion)
     */
    public function productos()
    {
        return $this->hasMany(\App\Models\Productos\ProductoPresentacion::class, 'presentacion_id');
    }
}
