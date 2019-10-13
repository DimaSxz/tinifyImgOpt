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

Route::get('/', 'CategoryController@index');

// pattern for parameter {id} is already set in /app/Providers/RouteServiceProvider.php
Route::get('/category/{id}', 'CategoryController@show');
Route::get('/product/{id}', 'ProductController@show');

Auth::routes();

Route::get('/admin', 'AdminController@index')->name('admin');

Route::post('/category', 'CategoryController@store');
Route::post('/category/{id}', 'CategoryController@update');
Route::get('/delcategory/{id}', 'CategoryController@destroy');

Route::post('/product', 'ProductController@store');
Route::post('/product/{id}', 'ProductController@update');
Route::get('/delproduct/{id}', 'ProductController@destroy');

