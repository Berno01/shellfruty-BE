<?php

namespace App\Venta\Application\UseCases\Venta;

use App\Menu\Domain\Models\Categoria;
use Illuminate\Support\Facades\DB;

class GetIngredientesDictionaryUseCase
{
    public function execute(): array
    {
        return Categoria::select('categoria.id_categoria', 'categoria.nombre_categoria')
            ->where('categoria.estado', true)
            ->with(['ingredientes' => function ($query) {
                $query->select('id_ingrediente', 'nombre_ingrediente', 'id_categoria', 'url_foto')
                    ->where('estado', true);
            }])
            ->get()
            ->map(function ($categoria) {
                return [
                    'nombre_categoria' => $categoria->nombre_categoria,
                    'ingredientes' => $categoria->ingredientes->map(function ($ingrediente) {
                        return [
                            'id_ingrediente' => $ingrediente->id_ingrediente,
                            'nombre_ingrediente' => $ingrediente->nombre_ingrediente,
                            'url_foto' => $ingrediente->url_foto
                        ];
                    })->toArray()
                ];
            })
            ->toArray();
    }
}
