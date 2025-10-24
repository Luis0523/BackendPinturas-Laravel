<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use App\Models\Productos\Producto;

class Marca extends Model
{
    /**
     * Nombre de la tabla asociada al modelo
     */
    protected $table = 'marcas';

    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'nombre',
        'activa',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'activa' => 'boolean',
    ];

    /**
     * Relación: Una marca tiene muchos productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'marca_id');
    }
}
