<?php

namespace App\Menu\Infrastructure\Providers;

use App\Menu\Application\Repositories\CategoriaRepositoryInterface;
use App\Menu\Application\Repositories\CategoriaMenuRepositoryInterface;
use App\Menu\Application\Repositories\IngredienteRepositoryInterface;
use App\Menu\Application\Repositories\MenuRepositoryInterface;
use App\Menu\Application\Repositories\ReglaRepositoryInterface;
use App\Menu\Infrastructure\Repositories\EloquentCategoriaRepository;
use App\Menu\Infrastructure\Repositories\EloquentCategoriaMenuRepository;
use App\Menu\Infrastructure\Repositories\EloquentIngredienteRepository;
use App\Menu\Infrastructure\Repositories\EloquentMenuRepository;
use App\Menu\Infrastructure\Repositories\EloquentReglaRepository;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind Repositories
        $this->app->bind(
            CategoriaRepositoryInterface::class,
            EloquentCategoriaRepository::class
        );

        $this->app->bind(
            CategoriaMenuRepositoryInterface::class,
            EloquentCategoriaMenuRepository::class
        );

        $this->app->bind(
            IngredienteRepositoryInterface::class,
            EloquentIngredienteRepository::class
        );

        $this->app->bind(
            MenuRepositoryInterface::class,
            EloquentMenuRepository::class
        );

        $this->app->bind(
            ReglaRepositoryInterface::class,
            EloquentReglaRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
