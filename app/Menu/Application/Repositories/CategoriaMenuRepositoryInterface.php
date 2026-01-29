<?php

namespace App\Menu\Application\Repositories;

use App\Menu\Domain\Models\CategoriaMenu;
use Illuminate\Database\Eloquent\Collection;

interface CategoriaMenuRepositoryInterface
{
    public function findAll(): Collection;
    
    public function findById(int $id): ?CategoriaMenu;
    
    public function create(array $data): CategoriaMenu;
    
    public function update(int $id, array $data): ?CategoriaMenu;
}
