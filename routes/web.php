<?php

use App\Http\Middleware\Cors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::get('/', 'App\Http\Controllers\HomeController@index');

Route::get('/searchtour', 'App\Http\Controllers\SearchTourController@index');

Route::get('/hottour', 'App\Http\Controllers\SearchTourController@index');

Route::get('/hotels', 'App\Http\Controllers\HotelsController@index');

Route::get('/admin', 'App\Http\Controllers\AdminController@index')->middleware('auth');

Route::post('/states', 'App\Http\Controllers\CountryController@getStates');

Route::post('/tours', 'App\Http\Controllers\RoomController@getTours');

Auth::routes();


