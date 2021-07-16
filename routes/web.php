<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index');

//Route::namespace('Auth')->group(function() {
//    Route::post('register', 'ApiRegisterController@Register');
//    Route::post('login', 'ApiLoginController@Login');
//});

Route::post('ticket', 'TicketController@add');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('ticket', 'TicketController@list');
    Route::post('buy-ticket', 'TicketController@buyTicket');
    Route::get('user-ticket', 'TicketController@userTicket');
});

Auth::routes();
Route::get('/google/auth', 'App\Http\Controllers\SocialiteController@redirectToProvider');
Route::get('/google/auth/callback', 'App\Http\Controllers\SocialiteController@handleProviderCallback');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/chat', 'ChatsController@index');
Route::get('/messages', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');
