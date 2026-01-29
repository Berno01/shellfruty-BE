<?php

namespace App\Menu\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingrediente extends Model
{
    protected $table = 'ingrediente';
    protected $primaryKey = 'id_ingrediente';
    public $timestamps = false;

    protected $fillable = [
        'nombre_ingrediente',
        'id_categoria',
        'estado',
        'url_foto'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'id_categoria' => 'integer'
    ];

    protected $hidden = [];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }
}
