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

// ANYONE ROUTES

Route::get('/validate_email', 'ValidateEmailController@index');


// GUEST ROUTES
Route::group(['middleware' => 'guest'], function(){
    Route::get('/signup', 'SignupController@index');
    Route::get('/check_infos', 'SignupController@check_infos');
    Route::post('/register', 'SignupController@register');
    Route::get('/signin', 'SigninController@index');
    Route::get('/try_to_login', 'SigninController@try_to_login');
    Route::post('/login', 'SigninController@login');
    Route::get('/forgot_password', 'ForgotPasswordController@index');
    Route::post('/send_reset_password_email', 'ForgotPasswordController@send_reset_password_email');
    Route::get('/reset_password', 'ResetPasswordController@index');
    Route::post('/set_password', 'ResetPasswordController@set');
});


// LOGGED ROUTES
Route::group(['middleware' => 'logged'], function(){
    Route::get('/logout', 'LogoutController@logout');
    Route::get('/home', 'HomeController@index');
    Route::get('/', 'HomeController@index');
    Route::get('/settings', 'SettingsController@index');
    Route::post('/reset_login', 'SettingsController@reset_login');
    Route::post('/send_email_reset_email', 'SettingsController@send_email_reset_email');
    Route::get('/set_new_password', 'SetNewPasswordController@index');
    Route::post('/check_new_password', 'SetNewPasswordController@check_new_password');
    Route::post('/update_password', 'SetNewPasswordController@update_password');
    Route::get('/buildings', 'BuildingsController@index');
    Route::post('/update_building', 'BuildingsController@update');
    Route::get('/techs', 'TechsController@index');
    Route::post('/update_tech', 'TechsController@update');
    Route::post('/interrupt', 'InterruptController@interrupt');
    Route::get('/forge', 'ForgeController@index');
});


