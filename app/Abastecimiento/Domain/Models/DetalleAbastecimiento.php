<?php

namespace App\Abastecimiento\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Menu\Domain\Models\Menu;

class DetalleAbastecimiento extends Model
{
    protected $table = 'detalle_abastecimiento';
    protected $primaryKey = 'id_detalle_abastecimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_abastecimiento',
        'id_menu',
        'cantidad'
    ];

    protected $casts = [
        'cantidad' => 'integer'
    ];

    protected $hidden = [];

    protected $appends = ['nombre_menu'];

    public function abastecimiento(): BelongsTo
    {
        return $this->belongsTo(Abastecimiento::class, 'id_abastecimiento', 'id_abastecimiento');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function getNombreMenuAttribute(): ?string
    {
        return $this->menu?->nombre_menu;
    }
}
