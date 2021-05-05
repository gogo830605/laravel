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

Route::get('/', 'App\Http\Controllers\WelcomeController@index');

Auth::routes();
Route::get('/google/auth', 'App\Http\Controllers\SocialiteController@redirectToProvider');
Route::get('/google/auth/callback', 'App\Http\Controllers\SocialiteController@handleProviderCallback');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
