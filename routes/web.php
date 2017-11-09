<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Auth::routes();

//blog view
Route::get('/', 'PostController@index');
Route::get('/post/{id}', 'PostController@show');

Route::get('/me', 'PostController@me');


//create and edit
Route::group(['middleware' => 'auth'], function () {

	Route::get('/message', 'PostController@message');
	Route::get('/create', 'PostController@create');
    Route::get('/post/{id}/edit', 'PostController@edit');
});


Route::get('/home', 'HomeController@index')->name('home');
