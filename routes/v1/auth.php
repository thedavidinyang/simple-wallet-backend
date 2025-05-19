<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'    => '/auth',
    'namespace' => 'Auth',
], function () {

    Route::post('sign-up', 'RegisterController@register');

    Route::post('sign-in', 'LoginController@signIn');
    Route::post('sign-out', 'LoginController@signOut')->middleware('jwt.verify', 'email.verify');


});
