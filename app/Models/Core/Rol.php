<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'roles';

    /**
     * Indicar que no usamos timestamps
     */
    public $timestamps = false;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'nombre'
    ];

    /**
     * Relación: Un rol puede tener muchos usuarios
     */
    public function usuarios()
    {
        return $this->hasMany(\App\Models\Usuarios\Usuario::class, 'rol_id');
    }
}
