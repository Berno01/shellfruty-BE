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
            ->addSelect(DB::raw('IFNULL(venta.is_updated, 0) as is_updated'))
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

    public function getHistoryByVentaId(int $id): ?array
    {
        $ventaActual = Venta::with(['detalles.personalizaciones'])->find($id);

        if (!$ventaActual) {
            return null;
        }

        $auditorias = DB::table('auditoria_venta')
            ->where('id_venta', $id)
            ->orderBy('created_at', 'asc')
            ->get(['id_auditoria', 'detalle', 'created_at']);

        $historial = [];

        foreach ($auditorias as $auditoria) {
            $detalle = json_decode((string) $auditoria->detalle, true);
            if (!is_array($detalle)) {
                $detalle = [];
            }

            $historial[] = [
                'version' => 0,
                'source' => 'AUDITORIA',
                'timestamp' => $auditoria->created_at,
                'id_auditoria' => (int) $auditoria->id_auditoria,
                'snapshot' => [
                    'venta' => $detalle['venta'] ?? null,
                    'detalles' => $detalle['detalles'] ?? [],
                    'metadata' => $detalle['metadata'] ?? [],
                ],
            ];
        }

        $historial[] = [
            'version' => 0,
            'source' => 'ACTUAL',
            'timestamp' => $ventaActual->updated_at?->toDateTimeString(),
            'id_auditoria' => null,
            'snapshot' => [
                'venta' => $this->mapVentaBaseData($ventaActual),
                'detalles' => $this->mapVentaDetalles($ventaActual),
                'metadata' => [
                    'edited_by' => $ventaActual->updated_by,
                    'edited_at' => $ventaActual->updated_at?->toDateTimeString(),
                ],
            ],
        ];

        foreach ($historial as $index => &$item) {
            $item['version'] = $index + 1;
        }
        unset($item);

        return [
            'id_venta' => $ventaActual->id_venta,
            'total_versiones' => count($historial),
            'historial' => $historial,
        ];
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
            $venta = Venta::with(['detalles.personalizaciones'])->find($id);

            if (!$venta) {
                return null;
            }

            $editedAt = now();
            $snapshot = [
                'venta' => $this->mapVentaBaseData($venta),
                'detalles' => $this->mapVentaDetalles($venta),
                'metadata' => [
                    'edited_by' => $userId,
                    'edited_at' => $editedAt->toDateTimeString(),
                ],
            ];

            DB::table('auditoria_venta')->insert([
                'id_venta' => $venta->id_venta,
                'detalle' => json_encode($snapshot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'created_at' => $editedAt,
            ]);

            unset($ventaData['fecha']);
            $ventaData['updated_by'] = $userId;
            $ventaData['is_updated'] = true;
            $venta->update($ventaData);

            // Eliminar detalles anteriores
            $idsDetallesActuales = $venta->detalles->pluck('id_detalle_venta');
            if ($idsDetallesActuales->isNotEmpty()) {
                DetallePersonalizacion::whereIn('id_detalle_venta', $idsDetallesActuales)->delete();
            }
            
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

    private function mapVentaBaseData(Venta $venta): array
    {
        return [
            'id_venta' => $venta->id_venta,
            'fecha' => $venta->fecha,
            'id_sucursal' => $venta->id_sucursal,
            'monto_efectivo' => $venta->monto_efectivo,
            'monto_qr' => $venta->monto_qr,
            'total' => $venta->total,
            'estado' => $venta->estado,
            'is_updated' => (bool) ($venta->is_updated ?? false),
            'created_at' => $venta->created_at,
            'updated_at' => $venta->updated_at,
            'created_by' => $venta->created_by,
            'updated_by' => $venta->updated_by,
        ];
    }

    private function mapVentaDetalles(Venta $venta): array
    {
        return $venta->detalles->map(function ($detalle) {
            return [
                'id_detalle_venta' => $detalle->id_detalle_venta,
                'id_menu' => $detalle->id_menu,
                'cantidad' => $detalle->cantidad,
                'precio_unitario' => $detalle->precio_unitario,
                'total' => $detalle->total,
                'personalizaciones' => $detalle->personalizaciones->map(function ($personalizacion) {
                    return [
                        'id_detalle_personalizacion' => $personalizacion->id_detalle_personalizacion,
                        'id_ingrediente' => $personalizacion->id_ingrediente,
                        'cantidad' => $personalizacion->cantidad ?? null,
                    ];
                })->values()->toArray(),
            ];
        })->values()->toArray();
    }
}
