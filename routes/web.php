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
Route::get('/thought/{post_id}', 'postsController@view')->name('posts.view');

Route::post('/thought/create', 'postsController@create')->name('posts.create');
Route::post('/thought/comment', 'postControllers@comment')->name('posts.comment');
Route::post('/thought/fetchAll', 'postControllers@fetchAll')->name('posts.fetchAll');
Route::post('/thought/like', 'postsController@like')->name('posts.like');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
