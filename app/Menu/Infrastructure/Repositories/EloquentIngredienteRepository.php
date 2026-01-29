<?php

namespace App\Menu\Infrastructure\Repositories;

use App\Menu\Domain\Models\Ingrediente;
use App\Menu\Application\Repositories\IngredienteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentIngredienteRepository implements IngredienteRepositoryInterface
{
    public function findByCategoria(int $idCategoria): Collection
    {
        return Ingrediente::where('id_categoria', $idCategoria)
            ->where('estado', true)
            ->select('id_ingrediente', 'nombre_ingrediente', 'url_foto')
            ->get();
    }

    public function findById(int $id): ?Ingrediente
    {
        return Ingrediente::where('id_ingrediente', $id)
            ->where('estado', true)
            ->with('categoria')
            ->first();
    }

    public function create(array $data): Ingrediente
    {
        $data['estado'] = true;
        return Ingrediente::create($data);
    }

    public function update(int $id, array $data): ?Ingrediente
    {
        $ingrediente = Ingrediente::where('id_ingrediente', $id)
            ->where('estado', true)
            ->first();

        if (!$ingrediente) {
            return null;
        }

        $ingrediente->update($data);
        return $ingrediente->fresh(['categoria']);
    }

    public function delete(int $id): bool
    {
        $ingrediente = Ingrediente::where('id_ingrediente', $id)->first();

        if (!$ingrediente) {
            return false;
        }

        $ingrediente->estado = false;
        return $ingrediente->save();
    }
}
