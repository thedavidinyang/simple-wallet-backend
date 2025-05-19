<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class verifyPhone
{

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

  

        return $next($request);
    }
}
