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


Route::group(['middleware' => ['auth','revalidate']], function(){
	Route::get('/module', 'HomeController@module')->name('module');
	Route::post('/chosen', 'HomeController@chosen');

	Route::get('type', 'TypesController@index');
	Route::post('type/add', 'TypesController@store');
	Route::get('type/getType/{id}', 'TypesController@show');
	Route::patch('type/{type}', 'TypesController@update');
	Route::get('type/search', 'TypesController@search');
	Route::delete('type/{type}','TypesController@destroy');
	Route::get('type/export','TypesController@export');
	Route::post('type/import','TypesController@import');

	Route::get('component', 'ComponentsController@index');
	Route::post('component/add', 'ComponentsController@store');
	Route::get('component/getComponent/{id}', 'ComponentsController@show');
	Route::patch('component/{component}', 'ComponentsController@update');
	Route::get('component/search', 'ComponentsController@search');
	Route::delete('component/{component}','ComponentsController@destroy');
	Route::get('component/export','ComponentsController@export');
	Route::post('component/import','ComponentsController@import');

	Route::get('product', 'ProductsController@index')->name('product');
	Route::get('product/create', 'ProductsController@create');
	Route::post('product/add', 'ProductsController@store');
	Route::get('product/getProduct/{id}', 'ProductsController@show');
	Route::get('product/getProductComponents/{id}', 'ProductsController@components');
	Route::get('product/{product}/edit', 'ProductsController@edit');
	Route::patch('product/{product}', 'ProductsController@update');
	Route::get('product/search', 'ProductsController@search');
	Route::delete('product/{product}','ProductsController@destroy');
	Route::get('product/cost/{product}', 'ProductsController@cost');
	Route::get('product/export','ProductsController@export');
	Route::post('product/import','ProductsController@import');

	Route::get('user', 'UsersController@index');
	Route::get('user/getUser/{id}', 'UsersController@show');
	Route::get('user/search', 'UsersController@search');
	Route::patch('user/{user}', 'UsersController@update');
	Route::delete('user/{user}', 'UsersController@destroy');
	Route::get('user/reset/{user}', 'UsersController@resetForm');
	Route::post('user/reset/{user}','UsersController@updatePassword');
	Route::get('user/export','UsersController@export');
	Route::post('user/import','UsersController@import');

	Route::get('category', 'CategoryController@index');
	Route::post('category/add', 'CategoryController@store');
	Route::get('category/getCategory/{id}', 'CategoryController@show');
	Route::patch('category/{category}', 'CategoryController@update');
	Route::get('category/search', 'CategoryController@search');
	Route::delete('category/{category}','CategoryController@destroy');
	Route::get('category/export','CategoryController@export');
	Route::post('category/import','CategoryController@import');

	Route::get('article', 'ArticleController@index');
	Route::post('article/add', 'ArticleController@store');
	Route::get('article/getArticle/{id}', 'ArticleController@show');
	Route::patch('article/{article}', 'ArticleController@update');
	Route::get('article/search', 'ArticleController@search');
	Route::delete('article/{article}','ArticleController@destroy');
	Route::get('article/export','ArticleController@export');
	Route::post('article/import','ArticleController@import');

	Route::get('location', 'LocationController@index');
	Route::post('location/add', 'LocationController@store');
	Route::get('location/getLocation/{id}', 'LocationController@show');
	Route::patch('location/{location}', 'LocationController@update');
	Route::get('location/search', 'LocationController@search');
	Route::delete('location/{location}','LocationController@destroy');
	Route::get('location/export','LocationController@export');
	Route::post('location/import','LocationController@import');

	Route::get('record', 'RecordController@index');
	Route::post('record/add', 'RecordController@store');
	Route::get('record/getRecord/{id}', 'RecordController@show');
	Route::patch('record/{record}', 'RecordController@update');
	Route::get('record/search', 'RecordController@search');
	Route::delete('record/{record}','RecordController@destroy');
	Route::get('record/export','RecordController@export');
	Route::post('record/import','RecordController@import');
	
	Route::get('inventory','RecordController@inventory');	
	Route::get('inventory/move/{article}','RecordController@move');
	Route::get('inventory/search', 'RecordController@searchInventory');
});