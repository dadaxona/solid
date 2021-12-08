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

//CRM
Route::resource('crm/expenses', ExpenseController::class);
Route::get('crm/clients/find/{identifier}', 'ClientController@find');
Route::resource('crm/clients', ClientController::class);
Route::resource('crm/deals', DealController::class);
Route::resource('crm/tasks', TaskController::class);
Route::resource('crm/notifications', NotificationController::class);
Route::resource('crm/orders', OrderController::class);