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

        if($user->phone_verified_at == null){
            return response()->json([
                'status'  => false,
                'message' => 'Verify phone number to proceed',
                'errors'  => ['phone_unverified' => 'Verify phone number to proceed.'],
            ], 403);
        }

        return $next($request);
    }
}
