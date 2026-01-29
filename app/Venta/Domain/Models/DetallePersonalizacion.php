<?php

namespace App\Venta\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePersonalizacion extends Model
{
    protected $table = 'detalle_personalizacion';
    protected $primaryKey = 'id_detalle_personalizacion';
    public $timestamps = false;

    protected $fillable = [
        'id_detalle_venta',
        'id_ingrediente'
    ];

    protected $casts = [
        'id_detalle_venta' => 'integer',
        'id_ingrediente' => 'integer'
    ];

    protected $hidden = [];

    public function detalleVenta(): BelongsTo
    {
        return $this->belongsTo(DetalleVenta::class, 'id_detalle_venta', 'id_detalle_venta');
    }

    public function ingrediente(): BelongsTo
    {
        return $this->belongsTo(\App\Menu\Domain\Models\Ingrediente::class, 'id_ingrediente', 'id_ingrediente');
    }
}
