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

Route::get('/', 'indexController@index')->name('index.index');

// Posts (Requests)
Route::get('/post/{post_id}', 'postsController@view')->name('posts.view');

Route::post('/post/create', 'postsController@create')->name('posts.create');
Route::post('/post/share', 'postControllers@share')->name('posts.share');

