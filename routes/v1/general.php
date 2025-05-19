<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'    => '/general',
    'namespace' => 'General',
], function () {

    Route::resources([
        'countries' => 'CountryController',
    ]);

});
