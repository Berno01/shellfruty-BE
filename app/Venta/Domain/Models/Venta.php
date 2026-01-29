<?php

namespace App\Venta\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'id_venta';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'fecha',
        'id_sucursal',
        'monto_efectivo',
        'monto_qr',
        'total',
        'created_by',
        'updated_by',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'monto_efectivo' => 'float',
        'monto_qr' => 'float',
        'total' => 'float',
        'id_sucursal' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'created_by', 'id_usuario');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'updated_by', 'id_usuario');
    }
}
