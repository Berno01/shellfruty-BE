<?php

namespace App\Menu\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaMenu extends Model
{
    protected $table = 'categoria_menu';
    protected $primaryKey = 'id_categoria_menu';
    public $timestamps = false;

    protected $fillable = [
        'nombre_categoria_menu',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    protected $hidden = [];
}
