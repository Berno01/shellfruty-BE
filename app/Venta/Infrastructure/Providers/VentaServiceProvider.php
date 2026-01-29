<?php

namespace App\Venta\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Venta\Application\Repositories\VentaRepositoryInterface;
use App\Venta\Infrastructure\Repositories\EloquentVentaRepository;

class VentaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(VentaRepositoryInterface::class, EloquentVentaRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
