<?php

namespace App\Venta\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre_rol'
    ];

    protected $hidden = [];
}
