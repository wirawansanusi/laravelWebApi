<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function() { return view('welcome'); });
Route::get('category', ['middleware' => 'auth', function(){ return view('index'); }]);
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');
//Route::get('register', 'Auth\AuthController@getRegister');
//Route::post('register', 'Auth\AuthController@postRegister');

Route::group(['prefix' => 'api'], function(){

	Route::resource('category', 'CategoryController', ['except' => ['create', 'edit']]);
	Route::resource('category.post', 'PostController', ['except' => ['create', 'edit']]);
	Route::resource('category.post.thumbnail', 'PostThumbnailController', ['except' => ['create', 'edit', 'update']]);
	Route::delete('category/{category}/post/{post}/thumbnail', [
		'as' => 'api.category.post.thumbnail.destroyPost', 
		'uses' => 'PostThumbnailController@destroyPost'
	]);
});

