<?php

namespace App\Menu\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Regla extends Model
{
    protected $table = 'regla';
    protected $primaryKey = 'id_regla';
    public $timestamps = false;

    protected $fillable = [
        'id_categoria',
        'id_menu',
        'cant_gratis',
        'costo_extra',
        'combinacion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'combinacion' => 'boolean',
        'id_categoria' => 'integer',
        'id_menu' => 'integer',
        'cant_gratis' => 'integer',
        'costo_extra' => 'integer'
    ];

    protected $hidden = [];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleRegla::class, 'id_regla', 'id_regla');
    }

    public function detallesActivos(): HasMany
    {
        return $this->hasMany(DetalleRegla::class, 'id_regla', 'id_regla')
            ->where('estado', true);
    }
}
