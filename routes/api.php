<?php

Route::get('/post', 'PostController@index');
Route::get('/post/tag/{tagId}', 'PostController@tag');

Route::get('/post/{id}', 'PostController@show');
Route::get('/message', 'PostController@message');


//create and edit
Route::group(['middleware' => 'auth'], function () {

    Route::post('/post', 'PostController@save');
	Route::post('/postMessage', 'PostController@saveMessage');
    Route::put('/post/{id}', 'PostController@update');

    Route::delete('/post/{id}', 'PostController@destroy');
});