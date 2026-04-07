<?php

namespace App\Abastecimiento\Infrastructure\Repositories;

use App\Abastecimiento\Application\Repositories\AbastecimientoRepositoryInterface;
use App\Abastecimiento\Domain\Models\Abastecimiento;
use App\Abastecimiento\Domain\Models\DetalleAbastecimiento;
use Illuminate\Support\Facades\DB;

class EloquentAbastecimientoRepository implements AbastecimientoRepositoryInterface
{
    public function findAll(string $fecha, ?int $idSucursal)
    {
        $query = Abastecimiento::with('detalles.menu')
            ->whereDate('fecha', $fecha)
            ->where('estado', true);

        if ($idSucursal !== null) {
            $query->where('id_sucursal', $idSucursal);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function findById(int $id): ?Abastecimiento
    {
        return Abastecimiento::with('detalles.menu')
            ->where('id_abastecimiento', $id)
            ->where('estado', true)
            ->first();
    }

    public function create(array $data, int $userId): Abastecimiento
    {
        DB::beginTransaction();
        try {
            $abastecimiento = Abastecimiento::create([
                'fecha' => $data['fecha'],
                'id_sucursal' => $data['id_sucursal'],
                'estado' => true,
                'created_at' => now(),
                'created_by' => $userId,
                'updated_at' => now(),
                'updated_by' => $userId
            ]);

            foreach ($data['detalles'] as $detalle) {
                DetalleAbastecimiento::create([
                    'id_abastecimiento' => $abastecimiento->id_abastecimiento,
                    'id_menu' => $detalle['id_menu'],
                    'cantidad' => $detalle['cantidad']
                ]);
            }

            DB::commit();
            return $abastecimiento->load('detalles.menu');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data, int $userId): ?Abastecimiento
    {
        DB::beginTransaction();
        try {
            $abastecimiento = Abastecimiento::where('id_abastecimiento', $id)
                ->where('estado', true)
                ->first();

            if (!$abastecimiento) {
                DB::rollBack();
                return null;
            }

            $abastecimiento->update([
                'fecha' => $data['fecha'],
                'id_sucursal' => $data['id_sucursal'],
                'updated_at' => now(),
                'updated_by' => $userId
            ]);

            // Eliminar detalles anteriores
            DetalleAbastecimiento::where('id_abastecimiento', $id)->delete();

            // Crear nuevos detalles
            foreach ($data['detalles'] as $detalle) {
                DetalleAbastecimiento::create([
                    'id_abastecimiento' => $abastecimiento->id_abastecimiento,
                    'id_menu' => $detalle['id_menu'],
                    'cantidad' => $detalle['cantidad']
                ]);
            }

            DB::commit();
            return $abastecimiento->load('detalles.menu');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $abastecimiento = Abastecimiento::where('id_abastecimiento', $id)
            ->where('estado', true)
            ->first();

        if (!$abastecimiento) {
            return false;
        }

        $abastecimiento->update(['estado' => false]);
        return true;
    }

    public function getResumen(string $fecha, ?int $idSucursal): array
    {
        $query = DB::table('detalle_abastecimiento')
            ->join('abastecimiento', 'detalle_abastecimiento.id_abastecimiento', '=', 'abastecimiento.id_abastecimiento')
            ->join('menu', 'detalle_abastecimiento.id_menu', '=', 'menu.id_menu')
            ->whereDate('abastecimiento.fecha', $fecha)
            ->where('abastecimiento.estado', true);

        if ($idSucursal !== null) {
            $query->where('abastecimiento.id_sucursal', $idSucursal);
        }

        $result = $query->select(
                'menu.id_menu',
                'menu.nombre_menu',
                DB::raw('SUM(detalle_abastecimiento.cantidad) as total')
            )
            ->groupBy('menu.id_menu', 'menu.nombre_menu')
            ->orderBy('menu.nombre_menu')
            ->get();

        return $result->map(function ($item) {
            return [
                'id_menu' => $item->id_menu,
                'nombre_menu' => $item->nombre_menu,
                'total' => (int)$item->total
            ];
        })->toArray();
    }

    public function getSaldo(string $fecha, ?int $idSucursal): array
    {
        // Abastecido del día por menú
        $abastecidoQuery = DB::table('detalle_abastecimiento')
            ->join('abastecimiento', 'detalle_abastecimiento.id_abastecimiento', '=', 'abastecimiento.id_abastecimiento')
            ->join('menu', 'detalle_abastecimiento.id_menu', '=', 'menu.id_menu')
            ->whereDate('abastecimiento.fecha', $fecha)
            ->where('abastecimiento.estado', true);

        if ($idSucursal !== null) {
            $abastecidoQuery->where('abastecimiento.id_sucursal', $idSucursal);
        }

        $abastecido = $abastecidoQuery
            ->select('menu.id_menu', 'menu.nombre_menu', DB::raw('SUM(detalle_abastecimiento.cantidad) as abastecido'))
            ->groupBy('menu.id_menu', 'menu.nombre_menu')
            ->get()
            ->keyBy('id_menu');

        // Vendido del día (ENTREGADO o ENVIADO) por menú
        $vendidoQuery = DB::table('detalle_venta')
            ->join('venta', 'detalle_venta.id_venta', '=', 'venta.id_venta')
            ->whereDate('venta.fecha', $fecha)
            ->whereIn('venta.estado', ['ENTREGADO', 'ENVIADO']);

        if ($idSucursal !== null) {
            $vendidoQuery->where('venta.id_sucursal', $idSucursal);
        }

        $vendido = $vendidoQuery
            ->join('menu', 'detalle_venta.id_menu', '=', 'menu.id_menu')
            ->select('detalle_venta.id_menu', 'menu.nombre_menu', DB::raw('SUM(detalle_venta.cantidad) as vendido'))
            ->groupBy('detalle_venta.id_menu', 'menu.nombre_menu')
            ->get()
            ->keyBy('id_menu');

        // Merge: incluir menús abastecidos y también menús solo vendidos
        $result = [];
        $allMenuIds = $abastecido->keys()->merge($vendido->keys())->unique();

        foreach ($allMenuIds as $idMenu) {
            $abastecidoItem = $abastecido->get($idMenu);
            $vendidoItem = $vendido->get($idMenu);

            $cantAbastecido = $abastecidoItem ? (int)$abastecidoItem->abastecido : 0;
            $cantVendido = $vendidoItem ? (int)$vendidoItem->vendido : 0;

            $nombreMenu = $abastecidoItem->nombre_menu
                ?? $vendidoItem->nombre_menu
                ?? '';

            $result[] = [
                'id_menu'       => $idMenu,
                'nombre_menu'   => $nombreMenu,
                'abastecido'    => $cantAbastecido,
                'vendido'       => $cantVendido,
                'saldo'         => $cantAbastecido - $cantVendido,
            ];
        }

        // Ordenar por nombre
        usort($result, fn($a, $b) => strcmp($a['nombre_menu'], $b['nombre_menu']));

        return $result;
    }
}
