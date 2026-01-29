<?php

namespace App\Venta\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'estado',
        'id_rol',
        'id_sucursal'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'id_rol' => 'integer',
        'id_sucursal' => 'integer'
    ];

    protected $hidden = ['password'];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }
}
