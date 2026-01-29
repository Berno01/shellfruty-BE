<?php

namespace App\Menu\Infrastructure\Repositories;

use App\Menu\Domain\Models\Regla;
use App\Menu\Domain\Models\DetalleRegla;
use App\Menu\Application\Repositories\ReglaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EloquentReglaRepository implements ReglaRepositoryInterface
{
    public function findByCategoria(int $idCategoria): Collection
    {
        return Regla::where('id_categoria', $idCategoria)
            ->where('estado', true)
            ->with(['detallesActivos.ingrediente:id_ingrediente,nombre_ingrediente'])
            ->get();
    }

    public function findById(int $id): ?Regla
    {
        return Regla::where('id_regla', $id)
            ->where('estado', true)
            ->with(['detallesActivos.ingrediente:id_ingrediente,nombre_ingrediente'])
            ->first();
    }

    public function create(array $reglaData, array $detalles): Regla
    {
        return DB::transaction(function () use ($reglaData, $detalles) {
            $reglaData['estado'] = true;
            $regla = Regla::create($reglaData);

            foreach ($detalles as $detalle) {
                DetalleRegla::create([
                    'id_regla' => $regla->id_regla,
                    'id_ingrediente' => $detalle['id_ingrediente'],
                    'costo_extra' => $detalle['costo_extra'],
                    'estado' => true
                ]);
            }

            return $regla->load(['detallesActivos.ingrediente:id_ingrediente,nombre_ingrediente']);
        });
    }

    public function update(int $id, array $reglaData, array $detalles): ?Regla
    {
        return DB::transaction(function () use ($id, $reglaData, $detalles) {
            $regla = Regla::where('id_regla', $id)
                ->where('estado', true)
                ->first();

            if (!$regla) {
                return null;
            }

            $regla->update($reglaData);

            // Soft delete de detalles anteriores
            DetalleRegla::where('id_regla', $id)->update(['estado' => false]);

            // Crear nuevos detalles
            foreach ($detalles as $detalle) {
                DetalleRegla::create([
                    'id_regla' => $regla->id_regla,
                    'id_ingrediente' => $detalle['id_ingrediente'],
                    'costo_extra' => $detalle['costo_extra'],
                    'estado' => true
                ]);
            }

            return $regla->fresh(['detallesActivos.ingrediente:id_ingrediente,nombre_ingrediente']);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $regla = Regla::where('id_regla', $id)->first();

            if (!$regla) {
                return false;
            }

            // Soft delete de la regla
            $regla->estado = false;
            $regla->save();

            // Soft delete de los detalles
            DetalleRegla::where('id_regla', $id)->update(['estado' => false]);

            return true;
        });
    }
}
