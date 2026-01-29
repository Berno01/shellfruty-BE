<?php

namespace App\Sucursal\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Sucursal\Application\Repositories\SucursalRepositoryInterface;
use App\Sucursal\Infrastructure\Repositories\EloquentSucursalRepository;

class SucursalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SucursalRepositoryInterface::class, EloquentSucursalRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
