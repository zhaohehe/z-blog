<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//blog view
Route::get('/', 'PostController@index');
Route::get('/post/{id}', 'PostController@show');

Route::get('/me', 'PostController@me');

//create and edit
Route::group(['middleware' => 'auth'], function () {

    Route::get('/create', 'PostController@create');
    Route::get('/post/{id}/edit', 'PostController@edit');
});

//login
Route::get('/login', function (Request $request) {

    $credentials = $request->only('account', 'password');

    if (Auth::guard()->attempt($credentials, false)) {
        return redirect(url('/'));
    } else {
        return response()->json(['message' => 'wrong password!']);
    }
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect(url('/'));
});
