<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

Route::get('clientes', [ClientController::class, 'index']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

//Rotas para gerenciamento dos usuaŕios
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
});


Route::middleware([ 'auth:api', 'verified'])
    ->group(function () {

//Rotas para gerenciamento dos clientes
Route::get('/clientes', [ClientController::class, 'index']);
Route::get('/clientes/{id}', [ClientController::class, 'show']);
Route::post('clientes', [ClientController::class, 'store']);
Route::put('clientes/{id}', [ClientController::class, 'update']);
Route::delete('clientes/{id}', [ClientController::class, 'destroy']);

//Rotas para gerenciamento dos produtos
Route::get('produtos', [ProductController::class, 'index']);
Route::get('produtos/{id}', [ProductController::class, 'show']);
Route::post('produtos', [ProductController::class, 'store']);
Route::put('produtos/{id}', [ProductController::class, 'update']);
Route::delete('produtos/{id}', [ProductController::class, 'destroy']);

//Rotas para gerenciamento dos pedidos
Route::get('pedidos', [OrderController::class, 'index']);
Route::get('pedidos/{id}', [OrderController::class, 'show']);
Route::post('pedidos', [OrderController::class, 'store']);
Route::put('pedidos/{id}', [OrderController::class, 'update']);
Route::delete('pedidos/{id}', [OrderController::class, 'destroy']);
Route::put('pedidos/{id}/update-status', [OrderController::class, 'updateStatus']);

});
