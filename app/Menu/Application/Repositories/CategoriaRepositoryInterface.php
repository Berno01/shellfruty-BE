<?php

namespace App\Menu\Application\Repositories;

use App\Menu\Domain\Models\Categoria;
use Illuminate\Database\Eloquent\Collection;

interface CategoriaRepositoryInterface
{
    public function findAll(): Collection;
    
    public function findById(int $id): ?Categoria;
    
    public function create(array $data): Categoria;
    
    public function update(int $id, array $data): ?Categoria;
    
    public function delete(int $id): bool;
}
