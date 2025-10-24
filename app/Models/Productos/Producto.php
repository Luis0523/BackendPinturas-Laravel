<?php

namespace App\Models\Productos;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Marca;
use App\Models\Core\Categoria;

class Producto extends Model
{
    /**
     * Nombre de la tabla asociada al modelo
     */
    protected $table = 'productos';

    /**
     * Nombres personalizados para los timestamps
     */
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'categoria_id',
        'marca_id',
        'codigo_sku',
        'descripcion',
        'tamano',
        'duracion_anios',
        'extension_m2',
        'color',
        'activo',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'activo' => 'boolean',
        'duracion_anios' => 'integer',
        'extension_m2' => 'decimal:2',
    ];

    /**
     * Relación: Un producto pertenece a una marca
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    /**
     * Relación: Un producto pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
