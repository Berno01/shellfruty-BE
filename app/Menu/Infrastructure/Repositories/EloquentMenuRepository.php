<?php

namespace App\Menu\Infrastructure\Repositories;

use App\Menu\Domain\Models\Menu;
use App\Menu\Application\Repositories\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentMenuRepository implements MenuRepositoryInterface
{
    public function findAll(): Collection
    {
        return Menu::all();
    }

    public function findById(int $id): ?Menu
    {
        return Menu::where('id_menu', $id)
            ->where('estado', true)
            ->first();
    }

    public function findByIdWithReglas(int $id): ?Menu
    {
        return Menu::where('id_menu', $id)
            ->where('estado', true)
            ->with([
                'reglasActivas',
                'reglasActivas.detallesActivos'
            ])
            ->first();
    }

    public function create(array $data): Menu
    {
        $data['estado'] = true;
        return Menu::create($data);
    }

    public function update(int $id, array $data): ?Menu
    {
        $menu = Menu::where('id_menu', $id)
            ->where('estado', true)
            ->first();

        if (!$menu) {
            return null;
        }

        $menu->update($data);
        return $menu->fresh();
    }

    public function delete(int $id): bool
    {
        $menu = Menu::where('id_menu', $id)->first();

        if (!$menu) {
            return false;
        }

        $menu->estado = false;
        return $menu->save();
    }

    public function activate(int $id): bool
    {
        $menu = Menu::where('id_menu', $id)->first();

        if (!$menu) {
            return false;
        }

        $menu->estado = true;
        return $menu->save();
    }
}
