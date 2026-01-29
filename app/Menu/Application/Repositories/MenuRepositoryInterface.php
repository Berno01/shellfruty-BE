<?php

namespace App\Menu\Application\Repositories;

use App\Menu\Domain\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

interface MenuRepositoryInterface
{
    public function findAll(): Collection;
    
    public function findById(int $id): ?Menu;
    
    public function findByIdWithReglas(int $id): ?Menu;
    
    public function create(array $data): Menu;
    
    public function update(int $id, array $data): ?Menu;
    
    public function delete(int $id): bool;
    
    public function activate(int $id): bool;
}
