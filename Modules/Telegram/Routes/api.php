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


//Telegram
use Illuminate\Support\Facades\Route;

Route::resource('telegram/telegram-bots', 'TelegramBotController');
Route::resource('telegram/telegram-chats', 'TelegramChatController');
Route::get('telegram/telegram-users/download/{file_name}', 'TelegramUserController@exportFile')->name('telegram-users.export');
Route::resource('telegram/telegram-users', 'TelegramUserController');

Route::get('telegram/telegram-remove-webhook/{telegram_bot}', 'TelegramController@removeWebhook');
Route::get('telegram/telegram-set-webhook/{telegram_bot}', 'TelegramController@setWebhook');
Route::any('telegram/telegram-get-updates/{telegram_bot}', 'TelegramController@getUpdates')->name('telegram.get-updates');
Route::any('telegram/telegram-webhook-handler/{token}', 'TelegramController@webHookHandler')->name('telegram.webhook.handler');
Route::get('telegram/telegram-bot-statistics', 'TelegramController@statistics');

Route::resource('telegram/regions', 'RegionController');
Route::resource('telegram/directions', 'DirectionController');
Route::resource('telegram/job-applications', 'JobApplicationController');
Route::resource('telegram/announcements', 'AnnouncementController');