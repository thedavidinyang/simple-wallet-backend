<?php
use Illuminate\Support\Facades\Route;
//unproctected routes

Route::group([
    'prefix'    => '/admin',
    'namespace' => 'Admin',
], function () {

    Route::prefix('/auth')->namespace('Auth')->group(function () {

        Route::post('sign-in', 'LoginController');
    });

 
});

//protected routes
Route::group([
    'prefix'     => '/admin',
    'namespace'  => 'Admin',
    'middleware' => ['jwt.verify', 'is.staff'],
], function () {

    Route::prefix('/auth')->namespace('Auth')->group(function () {

        Route::post('sign-out', 'LogOutController');
    });


    Route::prefix('/config')->namespace('Config')->group(function () {

        Route::post('set-interbank-transfer-fee', 'SetInterTransferFeeController');
    });



});
