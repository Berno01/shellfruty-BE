<?php

namespace App\Sucursal\Infrastructure\Repositories;

use App\Sucursal\Domain\Models\Sucursal;
use App\Sucursal\Application\Repositories\SucursalRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentSucursalRepository implements SucursalRepositoryInterface
{
    public function getAll(): Collection
    {
        return Sucursal::select('id_sucursal', 'nombre_sucursal')
            ->where('estado', true)
            ->orderBy('nombre_sucursal', 'asc')
            ->get();
    }
}
