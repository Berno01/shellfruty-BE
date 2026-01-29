<?php

namespace App\Menu\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleRegla extends Model
{
    protected $table = 'detalle_regla';
    protected $primaryKey = 'id_detalle_regla';
    public $timestamps = false;

    protected $fillable = [
        'id_regla',
        'id_ingrediente',
        'costo_extra',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'id_regla' => 'integer',
        'id_ingrediente' => 'integer',
        'costo_extra' => 'float'
    ];

    protected $hidden = [];

    public function regla(): BelongsTo
    {
        return $this->belongsTo(Regla::class, 'id_regla', 'id_regla');
    }

    public function ingrediente(): BelongsTo
    {
        return $this->belongsTo(Ingrediente::class, 'id_ingrediente', 'id_ingrediente');
    }
}
