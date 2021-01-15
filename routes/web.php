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

Route::get('/hottour', 'App\Http\Controllers\SearchTourController@hot');

Route::get('/hotels', 'App\Http\Controllers\HotelsController@index');

Route::get('/admin', 'App\Http\Controllers\AdminController@index')->middleware('auth');

Auth::routes();

Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));
    $query = http_build_query([
        'client_id' => '1',
        'redirect_uri' => 'http:127.0.0.1:8000/',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
    ]);

    return redirect('http://127.0.0.1:8000/oauth/authorize?'.$query);
});

