<?php

namespace App\Menu\Application\Repositories;

use App\Menu\Domain\Models\Regla;
use Illuminate\Database\Eloquent\Collection;

interface ReglaRepositoryInterface
{
    public function findByCategoria(int $idCategoria): Collection;
    
    public function findById(int $id): ?Regla;
    
    public function create(array $reglaData, array $detalles): Regla;
    
    public function update(int $id, array $reglaData, array $detalles): ?Regla;
    
    public function delete(int $id): bool;
}
