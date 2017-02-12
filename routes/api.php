<?php

Route::get('/post', 'PostController@index');

Route::get('/post/{id}', 'PostController@show');

//create and edit
Route::group(['middleware' => 'auth'], function () {

    Route::post('/post', 'PostController@create');

    Route::put('/post/{id}', 'PostController@update');

    Route::delete('/post/{id}', 'PostController@destroy');
});