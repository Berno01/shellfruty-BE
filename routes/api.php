<?php

use App\Menu\Infrastructure\Controllers\CategoriaController;
use App\Menu\Infrastructure\Controllers\CategoriaMenuController;
use App\Menu\Infrastructure\Controllers\IngredienteController;
use App\Menu\Infrastructure\Controllers\MenuController;
use App\Menu\Infrastructure\Controllers\ReglaController;
use App\Venta\Infrastructure\Controllers\VentaController;
use App\Sucursal\Infrastructure\Controllers\SucursalController;
use App\Dashboard\Infrastructure\Controllers\DashboardController;
use App\Usuario\Infrastructure\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(CategoriaController::class)->prefix('menu')->group(function () {
        Route::get('/categoria', 'index');
        Route::post('/categoria', 'store');
        Route::put('/categoria/{id}', 'update');
        Route::delete('/categoria/{id}', 'destroy');
    });

    Route::controller(CategoriaMenuController::class)->prefix('menu')->group(function () {
        Route::get('/categoria-menu', 'index');
        Route::post('/categoria-menu', 'store');
        Route::put('/categoria-menu/{id}', 'update');
    });

    Route::controller(IngredienteController::class)->prefix('menu')->group(function () {
        Route::get('/ingrediente/{idCategoria}', 'index');
        Route::post('/ingrediente', 'store');
        Route::put('/ingrediente/{id}', 'update');
        Route::delete('/ingrediente/{id}', 'destroy');
    });

    Route::controller(MenuController::class)->group(function () {
        Route::get('/menu', 'index');
        Route::get('/menu/{id}', 'show');
        Route::post('/menu', 'store');
        Route::put('/menu/{id}', 'update');
        Route::patch('/menu/{id}/activar', 'activate');
        Route::delete('/menu/{id}', 'destroy');
    });

    Route::controller(ReglaController::class)->prefix('menu')->group(function () {
        Route::get('/reglas/{idCategoria}', 'index');
        Route::post('/reglas', 'store');
        Route::put('/reglas/{id}', 'update');
        Route::delete('/reglas/{id}', 'destroy');
    });

    Route::controller(VentaController::class)->group(function () {
        Route::get('/venta', 'index');
        Route::get('/venta/ingredientes', 'getIngredientes');
        Route::get('/venta/menus', 'getMenus');
        Route::get('/venta/{id}', 'show');
        Route::post('/venta', 'store');
        Route::put('/venta/{id}', 'update');
        Route::patch('/venta/{id}/enviar', 'enviar');
        Route::delete('/venta/{id}', 'destroy');
    });

    Route::controller(SucursalController::class)->group(function () {
        Route::get('/sucursal', 'index');
    });

    Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
        Route::get('/keys', 'getKeys');
        Route::get('/graphs', 'getGraphs');
    });

    Route::controller(UsuarioController::class)->prefix('usuario')->group(function () {
        Route::post('/login', 'login');
        Route::post('/hash-password', 'hashPassword'); // Temporal para generar hashes
    });
});
