<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/show/{id}', [ProductController::class, 'show']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/', [ProductController::class, 'update']);
    Route::delete('/', [ProductController::class, 'destroy']);
    Route::post('/adjust-stock', [ProductController::class, 'adjustStock']);
    Route::get('/low-stock', [ProductController::class, 'lowStock']);
});
