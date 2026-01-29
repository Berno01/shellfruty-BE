<?php

namespace App\Menu\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id_menu';
    public $timestamps = false;

    protected $fillable = [
        'nombre_menu',
        'precio_menu',
        'id_categoria_menu',
        'estado',
        'url_foto'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'precio_menu' => 'float',
        'id_categoria_menu' => 'integer'
    ];

    protected $hidden = [];

    public function categoriaMenu(): BelongsTo
    {
        return $this->belongsTo(CategoriaMenu::class, 'id_categoria_menu', 'id_categoria_menu');
    }

    public function reglas(): HasMany
    {
        return $this->hasMany(Regla::class, 'id_menu', 'id_menu');
    }

    public function reglasActivas(): HasMany
    {
        return $this->hasMany(Regla::class, 'id_menu', 'id_menu')
            ->where('estado', true);
    }
}
