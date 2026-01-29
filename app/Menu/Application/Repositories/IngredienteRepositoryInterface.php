<?php

namespace App\Menu\Application\Repositories;

use App\Menu\Domain\Models\Ingrediente;
use Illuminate\Database\Eloquent\Collection;

interface IngredienteRepositoryInterface
{
    public function findByCategoria(int $idCategoria): Collection;
    
    public function findById(int $id): ?Ingrediente;
    
    public function create(array $data): Ingrediente;
    
    public function update(int $id, array $data): ?Ingrediente;
    
    public function delete(int $id): bool;
}
