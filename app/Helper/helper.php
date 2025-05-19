<?php

use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Str;








function user()
{
    return auth()->user();
}





function apiMode()
{

    return env('API_MODE', 'test');
}





function generateTransactionRef($lenght = 19)
    {
        return 'WALLET' . Str::upper(Str::random($lenght));
    }

