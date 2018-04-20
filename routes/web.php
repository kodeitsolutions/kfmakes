<?php

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



Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('type', 'TypesController@index');
Route::post('type/add', 'TypesController@store');
Route::get('type/getType/{id}', 'TypesController@show');
Route::patch('type/{type}', 'TypesController@update');
Route::get('type/search', 'TypesController@search');
Route::delete('type/{type}','TypesController@destroy');

Route::get('component', 'ComponentsController@index');
Route::post('component/add', 'ComponentsController@store');
Route::get('component/getComponent/{id}', 'ComponentsController@show');
Route::patch('component/{component}', 'ComponentsController@update');
Route::get('component/search', 'ComponentsController@search');
Route::delete('component/{component}','ComponentsController@destroy');

Route::get('product', 'ProductsController@index');
Route::get('product/create', 'ProductsController@create');
Route::post('product/add', 'ProductsController@store');
Route::get('product/getProduct/{id}', 'ProductsController@show');
Route::get('product/{product}/edit', 'ProductsController@edit');
Route::patch('product/{product}', 'ProductsController@update');
Route::get('product/search', 'ProductsController@search');
Route::delete('product/{product}','ProductsController@destroy');
Route::get('product/cost/{product}', 'ProductsController@cost');

Route::get('user', 'UsersController@index');
Route::get('user/getUser/{id}', 'UsersController@show');
Route::get('user/search', 'UsersController@search');
Route::patch('user/{user}', 'UsersController@update');
Route::delete('user/{user}', 'UsersController@destroy');
Route::get('user/reset/{user}', 'UsersController@resetForm');
Route::post('user/reset/{user}','UsersController@updatePassword');
