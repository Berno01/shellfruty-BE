<?php

namespace App\Dashboard\Infrastructure\Repositories;

use App\Dashboard\Application\Repositories\DashboardRepositoryInterface;
use App\Venta\Domain\Models\Venta;
use App\Menu\Domain\Models\Ingrediente;
use App\Menu\Domain\Models\Categoria;
use App\Menu\Domain\Models\Menu;
use Illuminate\Support\Facades\DB;

class EloquentDashboardRepository implements DashboardRepositoryInterface
{
    public function getKeys(string $fechaInicio, string $fechaFin, ?int $idSucursal): array
    {
        $query = Venta::query()
            ->whereDate('fecha', '>=', $fechaInicio)
            ->whereDate('fecha', '<=', $fechaFin)
            ->whereIn('estado', ['ENVIADO', 'ENTREGADO']);

        if ($idSucursal !== null) {
            $query->where('id_sucursal', $idSucursal);
        }

        $totales = $query->selectRaw('
            SUM(monto_efectivo) as monto_efectivo_total,
            SUM(monto_qr) as monto_qr_total,
            SUM(total) as total_general
        ')->first();

        $totalVentasEnviado = Venta::query()
            ->whereDate('fecha', '>=', $fechaInicio)
            ->whereDate('fecha', '<=', $fechaFin)
            ->where('estado', 'ENVIADO')
            ->when($idSucursal !== null, function ($q) use ($idSucursal) {
                $q->where('id_sucursal', $idSucursal);
            })
            ->count();

        $cantidadTotalItems = DB::table('detalle_venta')
            ->join('venta', 'detalle_venta.id_venta', '=', 'venta.id_venta')
            ->whereDate('venta.fecha', '>=', $fechaInicio)
            ->whereDate('venta.fecha', '<=', $fechaFin)
            ->whereIn('venta.estado', ['ENVIADO', 'ENTREGADO'])
            ->when($idSucursal !== null, function ($q) use ($idSucursal) {
                $q->where('venta.id_sucursal', $idSucursal);
            })
            ->sum('detalle_venta.cantidad');

        return [
            'monto_efectivo_total' => $totales->monto_efectivo_total ?? 0,
            'monto_qr_total' => $totales->monto_qr_total ?? 0,
            'total_general' => $totales->total_general ?? 0,
            'cantidad_total_items' => $cantidadTotalItems ?? 0,
            'total_ventas_enviado' => $totalVentasEnviado
        ];
    }

    public function getIngredientesPorCategoria(string $fechaInicio, string $fechaFin, ?int $idSucursal): array
    {
        $query = DB::table('detalle_personalizacion')
            ->join('detalle_venta', 'detalle_personalizacion.id_detalle_venta', '=', 'detalle_venta.id_detalle_venta')
            ->join('venta', 'detalle_venta.id_venta', '=', 'venta.id_venta')
            ->join('ingrediente', 'detalle_personalizacion.id_ingrediente', '=', 'ingrediente.id_ingrediente')
            ->join('categoria', 'ingrediente.id_categoria', '=', 'categoria.id_categoria')
            ->whereDate('venta.fecha', '>=', $fechaInicio)
            ->whereDate('venta.fecha', '<=', $fechaFin)
            ->where('venta.estado', 'ENVIADO')
            ->when($idSucursal !== null, function ($q) use ($idSucursal) {
                $q->where('venta.id_sucursal', $idSucursal);
            })
            ->select(
                'categoria.id_categoria',
                'categoria.nombre_categoria',
                'ingrediente.id_ingrediente',
                'ingrediente.nombre_ingrediente',
                DB::raw('COUNT(detalle_personalizacion.id_ingrediente) as cantidad')
            )
            ->groupBy('categoria.id_categoria', 'categoria.nombre_categoria', 'ingrediente.id_ingrediente', 'ingrediente.nombre_ingrediente')
            ->orderBy('categoria.id_categoria')
            ->orderBy('cantidad', 'desc')
            ->get();

        // Agrupar por categoría
        $resultado = [];
        foreach ($query as $row) {
            if (!isset($resultado[$row->id_categoria])) {
                $resultado[$row->id_categoria] = [
                    'id_categoria' => $row->id_categoria,
                    'nombre_categoria' => $row->nombre_categoria,
                    'ingredientes' => []
                ];
            }
            
            $resultado[$row->id_categoria]['ingredientes'][] = [
                'nombre_ingrediente' => $row->nombre_ingrediente,
                'cantidad' => $row->cantidad
            ];
        }

        return array_values($resultado);
    }

    public function getMenusMasVendidos(string $fechaInicio, string $fechaFin, ?int $idSucursal): array
    {
        return DB::table('detalle_venta')
            ->join('venta', 'detalle_venta.id_venta', '=', 'venta.id_venta')
            ->join('menu', 'detalle_venta.id_menu', '=', 'menu.id_menu')
            ->whereDate('venta.fecha', '>=', $fechaInicio)
            ->whereDate('venta.fecha', '<=', $fechaFin)
            ->whereIn('venta.estado', ['ENVIADO', 'ENTREGADO'])
            ->when($idSucursal !== null, function ($q) use ($idSucursal) {
                $q->where('venta.id_sucursal', $idSucursal);
            })
            ->select(
                'menu.id_menu',
                'menu.nombre_menu',
                DB::raw('SUM(detalle_venta.cantidad) as cantidad')
            )
            ->groupBy('menu.id_menu', 'menu.nombre_menu')
            ->orderBy('cantidad', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'nombre_menu' => $item->nombre_menu,
                    'cantidad' => $item->cantidad
                ];
            })
            ->toArray();
    }

    public function getHorasConcurridas(string $fechaInicio, string $fechaFin, ?int $idSucursal): array
    {
        return DB::table('venta')
            ->whereDate('fecha', '>=', $fechaInicio)
            ->whereDate('fecha', '<=', $fechaFin)
            ->whereIn('estado', ['ENVIADO', 'ENTREGADO'])
            ->when($idSucursal !== null, function ($q) use ($idSucursal) {
                $q->where('id_sucursal', $idSucursal);
            })
            ->select(
                DB::raw('HOUR(fecha) as hora'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->groupBy(DB::raw('HOUR(fecha)'))
            ->orderBy('hora')
            ->get()
            ->map(function ($item) {
                return [
                    'hora' => $item->hora,
                    'cantidad' => $item->cantidad
                ];
            })
            ->toArray();
    }
}
