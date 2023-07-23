<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PedidoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(ClienteController::class)->group(function () {
    Route::get('/clientes', 'index');
    Route::get('/cliente/{id}', 'show');
    Route::post('/cliente', 'store');
    Route::put('/cliente/{id}', 'update');
    Route::delete('/cliente/{id}', 'destroy');
});

Route::controller(ProdutoController::class)->group(function () {
    Route::get('/produtos', 'index');
    Route::post('/produto', 'store');
    Route::get('/produto/{id}', 'show');
    Route::put('/produto/{id}', 'update');
    Route::delete('/produto/{id}', 'destroy');
});

Route::controller(ProdutoController::class)->group(function () {
    Route::post('/pedido', [PedidoController::class, 'store']);
    Route::get('/pedidos', [PedidoController::class, 'index']);
    Route::get('/pedido/{id}', [PedidoController::class, 'show']);
    Route::put('/pedido/{id}', [PedidoController::class, 'update']);
    Route::delete('/pedido/{id}', [PedidoController::class, 'destroy']);
});
