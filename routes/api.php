<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


$namespace = 'App\Http\Controllers';

Route::group([
    'prefix'=>'/v1',
    'namespace' => $namespace,
    'middleware' => ['json-response'],
], function(){


    require __DIR__ . '/v1/auth.php';
    require __DIR__ . '/v1/user.php';

});



