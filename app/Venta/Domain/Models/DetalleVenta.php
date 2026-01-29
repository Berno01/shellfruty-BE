<?php

namespace App\Venta\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetalleVenta extends Model
{
    protected $table = 'detalle_venta';
    protected $primaryKey = 'id_detalle_venta';
    public $timestamps = false;

    protected $fillable = [
        'id_venta',
        'id_menu',
        'cantidad',
        'precio_unitario',
        'total'
    ];

    protected $casts = [
        'id_venta' => 'integer',
        'id_menu' => 'integer',
        'cantidad' => 'integer',
        'precio_unitario' => 'float',
        'total' => 'float'
    ];

    protected $hidden = ['menu', 'id_venta'];

    protected $appends = ['nombre_menu', 'url_foto'];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(\App\Menu\Domain\Models\Menu::class, 'id_menu', 'id_menu');
    }

    public function personalizaciones(): HasMany
    {
        return $this->hasMany(DetallePersonalizacion::class, 'id_detalle_venta', 'id_detalle_venta');
    }

    public function getNombreMenuAttribute()
    {
        return $this->menu?->nombre_menu;
    }

    public function getUrlFotoAttribute()
    {
        return $this->menu?->url_foto;
    }
}
