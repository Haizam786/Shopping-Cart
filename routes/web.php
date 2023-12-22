<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
   return view('layout');

});

 
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

Route::get('/', 'App\Http\Controllers\ItemsController@index');

Route::get('cart', 'App\Http\Controllers\ItemsController@cart');

Route::get('add-to-cart/{id}', 'App\Http\Controllers\ItemsController@addToCart');

Route::patch('update-cart', 'App\Http\Controllers\ItemsController@update');

Route::delete('remove-from-cart', 'App\Http\Controllers\ItemsController@remove');

