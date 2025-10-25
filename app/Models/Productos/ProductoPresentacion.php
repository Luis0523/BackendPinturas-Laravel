<?php

namespace App\Models\Productos;

use Illuminate\Database\Eloquent\Model;
use App\Models\Productos\Producto;
use App\Models\Core\Presentacion;

class ProductoPresentacion extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'producto_presentacion';

    /**
     * Indicar que usamos timestamps personalizados
     */
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'producto_id',
        'presentacion_id',
        'activo'
    ];

    /**
     * Conversión de tipos (casting)
     */
    protected $casts = [
        'activo' => 'boolean',
        'producto_id' => 'integer',
        'presentacion_id' => 'integer'
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'activo' => true
    ];

    /**
     * Relación: Una producto_presentacion pertenece a un producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    /**
     * Relación: Una producto_presentacion pertenece a una presentación
     */
    public function presentacion()
    {
        return $this->belongsTo(Presentacion::class, 'presentacion_id');
    }

    /**
     * Scope para obtener solo registros activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para obtener catálogo completo con productos y presentaciones
     */
    public function scopeCatalogo($query)
    {
        return $query->where('activo', true)
            ->with(['producto.marca', 'producto.categoria', 'presentacion']);
    }

    /**
     * Scope para filtrar por producto
     */
    public function scopePorProducto($query, $productoId)
    {
        return $query->where('producto_id', $productoId);
    }

    /**
     * Scope para filtrar por presentación
     */
    public function scopePorPresentacion($query, $presentacionId)
    {
        return $query->where('presentacion_id', $presentacionId);
    }
}
