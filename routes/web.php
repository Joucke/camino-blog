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

Auth::routes(['register' => false]);

Route::get('/', 'ArticleController@index');
Route::resource('articles', 'ArticleController')->except('index');
Route::resource('photos', 'PhotoController');
Route::resource('locations', 'LocationController');

Route::get('/geocode-search', 'GeocodeController@search');
Route::get('/geocode-reverse', 'GeocodeController@reverse');

Route::get('/users/{user}', 'UserController@show');

Route::resource('tags', 'TagController')->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);

Route::get('/{year}/{month}', 'ArticleController@index')
    ->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}']);
