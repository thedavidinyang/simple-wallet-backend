<?php
use Illuminate\Support\Facades\Route;
//unproctected routes

Route::group([
    'prefix'    => '/user',
    'namespace' => 'User',
], function () {

    Route::get('/home', function () {
        return 123;
    });



});

//protected routes
Route::group([
    'prefix'     => '/user',
    'namespace'  => 'User',
    'middleware' => ['json-response','jwt.verify', 'email.verify'],
], function () {

    Route::prefix('/profile')->controller('Usercontroller')->group(function () {

        Route::get('/', 'getUserProfile');
    });





});
