<?php

namespace App\Menu\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';
    public $timestamps = false;

    protected $fillable = [
        'nombre_categoria',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    protected $hidden = [];

    public function ingredientes(): HasMany
    {
        return $this->hasMany(Ingrediente::class, 'id_categoria', 'id_categoria');
    }
}
