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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tmp_home', 'TmpController@index');

Route::get('/validate_email', 'ValidateEmailController@index');

Route::group(['middleware' => 'guest'], function(){
    Route::get('/signup', 'SignupController@index');
    Route::get('/check_infos', 'SignupController@check_infos');
    Route::post('/register', 'SignupController@register');
    Route::get('/signin', 'SigninController@index');
    Route::get('/try_to_login', 'SigninController@try_to_login');
    Route::post('/login', 'SigninController@login');
    Route::get('/forgot_password', 'ForgotPasswordController@index');
});
