<?php

namespace App\BotCatalog\Infrastructure\Repositories;

use App\BotCatalog\Application\Repositories\BotCatalogRepositoryInterface;
use App\Menu\Domain\Models\Menu;

class EloquentBotCatalogRepository implements BotCatalogRepositoryInterface
{
    public function getCatalog(): array
    {
        $menus = Menu::with([
            'reglas' => function ($q) {
                $q->where('estado', true)->with([
                    'categoria' => function ($q2) {
                        $q2->where('estado', true);
                    },
                    'detalles' => function ($q3) {
                        $q3->where('estado', true)->with(['ingrediente' => function ($q4) {
                            $q4->where('estado', true);
                        }]);
                    }
                ]);
            }
        ])->where('estado', true)->get();

        $result = [];
        foreach ($menus as $menu) {
            $menuArr = [
                'id' => $menu->id_menu,
                'nombre' => $menu->nombre_menu,
                'precio_base' => $menu->precio_menu,
                'reglas' => []
            ];
            foreach ($menu->reglas as $regla) {
                $reglaArr = [
                    'categoria' => $regla->categoria->nombre_categoria ?? null,
                    'gratis' => $regla->cant_gratis,
                    'precio_extra_regla' => $regla->costo_extra,
                    'permite_combinar' => (bool)$regla->combinacion,
                    'ingredientes' => []
                ];
                foreach ($regla->detalles as $detalle) {
                    if ($detalle->ingrediente) {
                        $reglaArr['ingredientes'][] = [
                            'id' => $detalle->ingrediente->id_ingrediente,
                            'nombre' => $detalle->ingrediente->nombre_ingrediente,
                            'extra' => $detalle->costo_extra
                        ];
                    }
                }
                $menuArr['reglas'][] = $reglaArr;
            }
            $result[] = $menuArr;
        }
        return ['menus' => $result];
    }
}
