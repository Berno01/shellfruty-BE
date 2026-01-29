<?php

namespace App\Venta\Infrastructure\Repositories;

use App\Venta\Domain\Models\Venta;
use App\Venta\Domain\Models\DetalleVenta;
use App\Venta\Domain\Models\DetallePersonalizacion;
use App\Venta\Domain\Models\Usuario;
use App\Venta\Application\Repositories\VentaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EloquentVentaRepository implements VentaRepositoryInterface
{
    public function findByDateRange(string $fechaInicio, string $fechaFin, ?int $idSucursal = null): Collection
    {
        $query = Venta::select('venta.*', 'usuario.username')
            ->join('usuario', 'venta.created_by', '=', 'usuario.id_usuario')
            ->whereDate('venta.fecha', '>=', $fechaInicio)
            ->whereDate('venta.fecha', '<=', $fechaFin)
            ->where('venta.estado', '!=', 'CANCELADO');

        if ($idSucursal !== null) {
            $query->where('venta.id_sucursal', $idSucursal);
        }

        // Debug temporal - eliminar después
        \Log::info('Query SQL:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'id_sucursal' => $idSucursal
        ]);

        return $query->orderBy('venta.fecha', 'desc')->get();
    }

    public function findById(int $id): ?Venta
    {
        return Venta::with([
            'detalles.menu',
            'detalles.personalizaciones'
        ])->find($id);
    }

    public function create(array $ventaData, array $detalles, int $userId): Venta
    {
        return DB::transaction(function () use ($ventaData, $detalles, $userId) {
            $ventaData['fecha'] = now();
            $ventaData['created_by'] = $userId;
            $ventaData['updated_by'] = $userId;
            
            // Determinar estado automáticamente
            $tienePersonalizaciones = false;
            foreach ($detalles as $detalle) {
                if (isset($detalle['personalizaciones']) && !empty($detalle['personalizaciones'])) {
                    $tienePersonalizaciones = true;
                    break;
                }
            }
            
            $ventaData['estado'] = $tienePersonalizaciones ? 'PENDIENTE' : 'ENTREGADO';
            
            $venta = Venta::create($ventaData);

            foreach ($detalles as $detalle) {
                $detalleVenta = DetalleVenta::create([
                    'id_venta' => $venta->id_venta,
                    'id_menu' => $detalle['id_menu'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio'],
                    'total' => $detalle['sub_total']
                ]);

                // Si tiene personalizaciones (bot)
                if (isset($detalle['personalizaciones']) && is_array($detalle['personalizaciones'])) {
                    foreach ($detalle['personalizaciones'] as $personalizacion) {
                        DetallePersonalizacion::create([
                            'id_detalle_venta' => $detalleVenta->id_detalle_venta,
                            'id_ingrediente' => $personalizacion['id_ingrediente'],
                            'cantidad' => $personalizacion['cantidad']
                        ]);
                    }
                }
            }

            return $venta->load(['detalles.personalizaciones']);
        });
    }

    public function update(int $id, array $ventaData, array $detalles, int $userId): ?Venta
    {
        return DB::transaction(function () use ($id, $ventaData, $detalles, $userId) {
            $venta = Venta::find($id);

            if (!$venta) {
                return null;
            }

            $ventaData['updated_by'] = $userId;
            $venta->update($ventaData);

            // Eliminar detalles anteriores
            DetallePersonalizacion::whereIn('id_detalle_venta', 
                $venta->detalles->pluck('id_detalle_venta')
            )->delete();
            
            DetalleVenta::where('id_venta', $id)->delete();

            // Crear nuevos detalles
            foreach ($detalles as $detalle) {
                $detalleVenta = DetalleVenta::create([
                    'id_venta' => $venta->id_venta,
                    'id_menu' => $detalle['id_menu'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio'],
                    'total' => $detalle['sub_total']
                ]);

                if (isset($detalle['personalizaciones']) && is_array($detalle['personalizaciones'])) {
                    foreach ($detalle['personalizaciones'] as $personalizacion) {
                        DetallePersonalizacion::create([
                            'id_detalle_venta' => $detalleVenta->id_detalle_venta,
                            'id_ingrediente' => $personalizacion['id_ingrediente'],
                            'cantidad' => $personalizacion['cantidad']
                        ]);
                    }
                }
            }

            return $venta->fresh(['detalles.personalizaciones']);
        });
    }

    public function cancel(int $id): bool
    {
        $venta = Venta::find($id);

        if (!$venta) {
            return false;
        }

        $venta->estado = 'CANCELADO';
        return $venta->save();
    }

    public function enviar(int $id): bool
    {
        $venta = Venta::find($id);

        if (!$venta) {
            return false;
        }

        $venta->estado = 'ENVIADO';
        return $venta->save();
    }

    public function getUserRole(int $userId): ?int
    {
        $usuario = Usuario::find($userId);
        return $usuario ? $usuario->id_rol : null;
    }
}
