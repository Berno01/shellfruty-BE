<?php

namespace App\Dashboard\Application\Repositories;

interface DashboardRepositoryInterface
{
    public function getKeys(string $fechaInicio, string $fechaFin, ?int $idSucursal): array;
    
    public function getIngredientesPorCategoria(string $fechaInicio, string $fechaFin, ?int $idSucursal): array;
    
    public function getMenusMasVendidos(string $fechaInicio, string $fechaFin, ?int $idSucursal): array;
    
    public function getHorasConcurridas(string $fechaInicio, string $fechaFin, ?int $idSucursal): array;
}
