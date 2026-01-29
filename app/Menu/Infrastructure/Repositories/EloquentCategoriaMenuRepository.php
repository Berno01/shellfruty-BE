<?php

namespace App\Menu\Infrastructure\Repositories;

use App\Menu\Domain\Models\CategoriaMenu;
use App\Menu\Application\Repositories\CategoriaMenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCategoriaMenuRepository implements CategoriaMenuRepositoryInterface
{
    public function findAll(): Collection
    {
        return CategoriaMenu::where('estado', true)->get();
    }

    public function findById(int $id): ?CategoriaMenu
    {
        return CategoriaMenu::where('id_categoria_menu', $id)
            ->where('estado', true)
            ->first();
    }

    public function create(array $data): CategoriaMenu
    {
        $data['estado'] = true;
        return CategoriaMenu::create($data);
    }

    public function update(int $id, array $data): ?CategoriaMenu
    {
        $categoriaMenu = CategoriaMenu::where('id_categoria_menu', $id)
            ->where('estado', true)
            ->first();

        if (!$categoriaMenu) {
            return null;
        }

        $categoriaMenu->update($data);
        return $categoriaMenu->fresh();
    }
}
