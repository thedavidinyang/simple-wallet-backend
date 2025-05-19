<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class VerifyJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $parsedToken = JWTAuth::manager()->decode($token);

        if (!$parsedToken) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        if (isset($parsedToken['exp']) && $parsedToken['exp'] < time()) {
            return response()->json(['error' => 'Token has expired'], 401);
        }

        $user = JWTAuth::authenticate($token);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 401);
        }

        return $next($request);
    }
}
