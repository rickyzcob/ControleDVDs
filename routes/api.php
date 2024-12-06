<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Rotas para gerenciamento dos clientes
Route::get('clientes', [ClientController::class, 'index']);
Route::get('clientes/{id}/view', [ClientController::class, 'show']);
Route::post('clientes', [ClientController::class, 'store']);
Route::put('clientes/{id}/update', [ClientController::class, 'update']);
Route::delete('clientes/{id}/delete', [ClientController::class, 'destroy']);

//Rotas para gerenciamento dos produtos
Route::get('produtos', [ProductController::class, 'index']);
Route::get('produtos/{id}/show', [ProductController::class, 'show']);
Route::post('produtos', [ProductController::class, 'store']);
Route::put('produtos/{id}/update', [ProductController::class, 'update']);
Route::delete('produtos/{id}/delete', [ProductController::class, 'destroy']);

//Rotas para gerenciamento dos pedidos
Route::get('pedidos', [OrderController::class, 'index']);
Route::get('pedidos/{id}/show', [OrderController::class, 'show']);
Route::post('pedidos', [OrderController::class, 'store']);
Route::put('pedidos/{id}/update', [OrderController::class, 'update']);
Route::delete('pedidos/{id}/delete', [OrderController::class, 'destroy']);
Route::put('pedidos/{id}/update-status', [OrderController::class, 'updateStatus']);
