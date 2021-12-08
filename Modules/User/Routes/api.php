<?php


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
use Illuminate\Support\Facades\Route;

Route::post('auth/login', 'AuthController@login');
Route::post('auth/register', 'AuthController@register');

//Users
Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', 'AuthController@logout');
    Route::get('/auth/me', 'AuthController@me');
    Route::get('/auth/refresh', 'AuthController@refresh');
    
    Route::resource('user/roles', RoleController::class);
    Route::resource('user/permissions', PermissionController::class);
    Route::get('user/users/find/{identifier}', 'UserController@find');
    Route::resource('user/users', UserController::class);
});
Route::resource('user/departments', DepartmentController::class);