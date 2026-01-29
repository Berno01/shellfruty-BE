<?php

namespace App\Menu\Infrastructure\Repositories;

use App\Menu\Domain\Models\Categoria;
use App\Menu\Application\Repositories\CategoriaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCategoriaRepository implements CategoriaRepositoryInterface
{
    public function findAll(): Collection
    {
        return Categoria::where('estado', true)->get();
    }

    public function findById(int $id): ?Categoria
    {
        return Categoria::where('id_categoria', $id)
            ->where('estado', true)
            ->first();
    }

    public function create(array $data): Categoria
    {
        $data['estado'] = true;
        return Categoria::create($data);
    }

    public function update(int $id, array $data): ?Categoria
    {
        $categoria = Categoria::where('id_categoria', $id)
            ->where('estado', true)
            ->first();

        if (!$categoria) {
            return null;
        }

        $categoria->update($data);
        return $categoria->fresh();
    }

    public function delete(int $id): bool
    {
        $categoria = Categoria::where('id_categoria', $id)->first();

        if (!$categoria) {
            return false;
        }

        $categoria->estado = false;
        return $categoria->save();
    }
}
