<?php

namespace App\Venta\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursal';
    protected $primaryKey = 'id_sucursal';
    public $timestamps = false;

    protected $fillable = [
        'nombre_sucursal',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    protected $hidden = [];
}
