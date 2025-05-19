<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class verifyEmail
{

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();


        if ( !$user->email_verified) {

            return response()->json([
                'status'  => false,
                'message' => 'verify email to proceed',
                'error'   => 'unverified email',
            ], 403);
        }

        return $next($request);
    }

}
