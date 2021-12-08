<?php
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// WMS
Route::resource('wms/products', ProductController::class);
Route::resource('wms/product-categories', ProductCategoryController::class);
Route::resource('wms/warehouses', WarehouseController::class);
Route::resource('wms/product-transactions', ProductTransactionController::class);
Route::resource('wms/units', UnitController::class);