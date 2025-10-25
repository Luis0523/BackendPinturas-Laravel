<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'sucursales';

    /**
     * Indicar que no usamos timestamps
     */
    public $timestamps = false;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'nombre',
        'direccion',
        'gps_lat',
        'gps_lng',
        'telefono',
        'activa'
    ];

    /**
     * Conversión de tipos (casting)
     */
    protected $casts = [
        'gps_lat' => 'decimal:6',
        'gps_lng' => 'decimal:6',
        'activa' => 'boolean'
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'activa' => true
    ];

    /**
     * Relación: Una sucursal puede tener muchos usuarios
     */
    public function usuarios()
    {
        return $this->hasMany(\App\Models\Usuarios\Usuario::class, 'sucursal_id');
    }

    /**
     * Relación: Una sucursal puede tener muchas órdenes de compra
     */
    public function ordenesCompra()
    {
        return $this->hasMany(\App\Models\Compras\OrdenCompra::class, 'sucursal_id');
    }

    /**
     * Relación: Una sucursal puede tener muchas facturas
     */
    public function facturas()
    {
        return $this->hasMany(\App\Models\Ventas\Factura::class, 'sucursal_id');
    }

    /**
     * Relación: Una sucursal puede tener muchos inventarios
     */
    public function inventarios()
    {
        return $this->hasMany(\App\Models\Inventario\InventarioSucursal::class, 'sucursal_id');
    }

    /**
     * Relación: Una sucursal puede tener muchos precios
     */
    public function precios()
    {
        return $this->hasMany(\App\Models\Inventario\Precio::class, 'sucursal_id');
    }

    /**
     * Relación: Una sucursal puede tener muchos movimientos de inventario
     */
    public function movimientos()
    {
        return $this->hasMany(\App\Models\Inventario\MovimientoInventario::class, 'sucursal_id');
    }

    /**
     * Relación: Una sucursal puede tener muchas recepciones
     */
    public function recepciones()
    {
        return $this->hasMany(\App\Models\Compras\Recepcion::class, 'sucursal_id');
    }
}
