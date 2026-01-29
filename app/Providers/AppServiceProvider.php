<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Menu Module
        $this->app->bind(
            \App\Menu\Application\Repositories\CategoriaRepositoryInterface::class,
            \App\Menu\Infrastructure\Repositories\EloquentCategoriaRepository::class
        );
        
        $this->app->bind(
            \App\Menu\Application\Repositories\IngredienteRepositoryInterface::class,
            \App\Menu\Infrastructure\Repositories\EloquentIngredienteRepository::class
        );
        
        $this->app->bind(
            \App\Menu\Application\Repositories\MenuRepositoryInterface::class,
            \App\Menu\Infrastructure\Repositories\EloquentMenuRepository::class
        );
        
        $this->app->bind(
            \App\Menu\Application\Repositories\ReglaRepositoryInterface::class,
            \App\Menu\Infrastructure\Repositories\EloquentReglaRepository::class
        );
        
        $this->app->bind(
            \App\Menu\Application\Repositories\CategoriaMenuRepositoryInterface::class,
            \App\Menu\Infrastructure\Repositories\EloquentCategoriaMenuRepository::class
        );

        // Venta Module
        $this->app->bind(
            \App\Venta\Application\Repositories\VentaRepositoryInterface::class,
            \App\Venta\Infrastructure\Repositories\EloquentVentaRepository::class
        );

        // Sucursal Module
        $this->app->bind(
            \App\Sucursal\Application\Repositories\SucursalRepositoryInterface::class,
            \App\Sucursal\Infrastructure\Repositories\EloquentSucursalRepository::class
        );

        // Dashboard Module
        $this->app->bind(
            \App\Dashboard\Application\Repositories\DashboardRepositoryInterface::class,
            \App\Dashboard\Infrastructure\Repositories\EloquentDashboardRepository::class
        );

        // Usuario Module
        $this->app->bind(
            \App\Usuario\Application\Repositories\UsuarioRepositoryInterface::class,
            \App\Usuario\Infrastructure\Repositories\EloquentUsuarioRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
