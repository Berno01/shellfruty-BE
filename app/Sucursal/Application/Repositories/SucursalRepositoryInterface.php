<?php

namespace App\Sucursal\Application\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface SucursalRepositoryInterface
{
    public function getAll(): Collection;
}
