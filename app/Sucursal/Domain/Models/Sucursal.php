<?php

namespace App\Sucursal\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursal';
    protected $primaryKey = 'id_sucursal';
    public $timestamps = false;

    protected $fillable = [
        'nombre_sucursal',
        'direccion',
        'telefono',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    protected $hidden = ['direccion', 'telefono', 'estado'];
}
