<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use App\Models\Productos\Producto;

class Categoria extends Model
{
    /**
     * Nombre de la tabla asociada al modelo
     */
    protected $table = 'categorias';

    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: Una categoría tiene muchos productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}
