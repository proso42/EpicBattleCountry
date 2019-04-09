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
    // Home endpoints
    Route::get('/home', 'HomeController@index');
    Route::post('/save_choice', 'HomeController@save_choice');
    Route::post('/rename_city', 'HomeController@rename_city');
    Route::get('/', 'HomeController@index');
    // Settings endpoints
    Route::get('/settings', 'SettingsController@index');
    Route::post('/reset_login', 'SettingsController@reset_login');
    Route::post('/send_email_reset_email', 'SettingsController@send_email_reset_email');
    Route::get('/set_new_password', 'SetNewPasswordController@index');
    Route::post('/check_new_password', 'SetNewPasswordController@check_new_password');
    Route::post('/update_password', 'SetNewPasswordController@update_password');
    // Buildings endpoints
    Route::get('/buildings', 'BuildingsController@index');
    Route::post('/update_building', 'BuildingsController@update');
    // Techs endpoints
    Route::get('/techs', 'TechsController@index');
    Route::post('/update_tech', 'TechsController@update');
    // Interrupt endpoints
    Route::post('/interrupt', 'InterruptController@interrupt');
    // Forge endpoints
    Route::get('/forge', 'ForgeController@index');
    Route::post('/calculate_price', 'ForgeController@calculate_price');
    Route::post('/craft_item', 'ForgeController@craft_item');
    // Army endpoints
    Route::get('/army', 'ArmyController@index');
    Route::post('calculate_training_price', 'ArmyController@calculate_training_price');
    Route::post('train_unit', 'ArmyController@train_unit');
    // Map endpoints
    Route::get('/map', 'MapController@index');
    //Exploration endpoints
    Route::get('/exploration', 'ExplorationController@index');
    Route::post('/time_explo', 'ExplorationController@time_explo');
    Route::post('/send_expedition', 'ExplorationController@send_expedition');
    // Messages endpoints
    Route::get('/messages', 'MessagesController@index');
    Route::post('/seen_msg', 'MessagesController@seen');
    Route::post('delete_msg', 'MessagesController@delete_msg');
    Route::post('/unlock_user', 'MessagesController@unlock_user');
});


