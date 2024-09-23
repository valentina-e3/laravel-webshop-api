<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/filter', [ProductController::class, 'filter']);
Route::get('/products/{SKU}', [ProductController::class, 'show']);

Route::get('/categories/{category}/products', [CategoryController::class, 'showProducts']);

Route::post('/orders/store', [OrderController::class, 'store']);
