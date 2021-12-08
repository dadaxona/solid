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

//LMS
Route::post('lms/login', 'AuthController@login');
Route::resource('lms/students', StudentController::class);
Route::resource('lms/attendances', AttendanceController::class);
Route::resource('lms/courses', CourseController::class);
Route::resource('lms/groups', GroupController::class);
Route::resource('lms/rooms', RoomController::class);
Route::resource('lms/teachers', TeacherController::class);
Route::resource('lms/lessons', LessonController::class);
Route::resource('lms/class-schedules', ClassScheduleController::class);

Route::get('lms/load-data-from-file', 'CertificateController@loadDataFromFile');
Route::get('lms/get-certificate/{id}', 'CertificateController@generateFile');