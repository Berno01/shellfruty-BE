<?php

namespace App\Sucursal\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Sucursal\Application\UseCases\ListSucursalesUseCase;

class SucursalController extends Controller
{
    public function __construct(
        private ListSucursalesUseCase $listSucursales
    ) {}

    public function index(): JsonResponse
    {
        $sucursales = $this->listSucursales->execute();

        return response()->json([
            'success' => true,
            'data' => $sucursales
        ]);
    }
}
