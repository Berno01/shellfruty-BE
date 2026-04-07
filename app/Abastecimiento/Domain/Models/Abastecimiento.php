<?php

namespace App\Abastecimiento\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Sucursal\Domain\Models\Sucursal;

class Abastecimiento extends Model
{
    protected $table = 'abastecimiento';
    protected $primaryKey = 'id_abastecimiento';
    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'id_sucursal',
        'estado',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $hidden = [];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleAbastecimiento::class, 'id_abastecimiento', 'id_abastecimiento');
    }
}
