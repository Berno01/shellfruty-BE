<?php

namespace App\Venta\Application\UseCases\Venta;

use App\Menu\Domain\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

class ListMenusActivosUseCase
{
    public function execute(): Collection
    {
        return Menu::select('id_menu', 'nombre_menu', 'precio_menu', 'id_categoria_menu', 'url_foto')
            ->where('estado', true)
            ->orderBy('nombre_menu', 'asc')
            ->get();
    }
}
