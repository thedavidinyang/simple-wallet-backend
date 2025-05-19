<?php

namespace App\Http\Middleware;

use App\Services\SecurityService;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
 use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class VerifyJWTToken
{
    public function handle($request, Closure $next)
    {
        try {
            $token = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token is invalid'
                ], Response::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'status' =>false,
                    'message' => 'Token has expired'
                ], Response::HTTP_UNAUTHORIZED);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Token not found'
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        if (! Auth::user()) {
            return response()->json([
                'status' =>false,
                'message' => 'Unauthenticated'
            ], Response::HTTP_UNAUTHORIZED);
        };

        // //Final check: does token exist on database?
        //   $jwt_token = $request->header('Authorization');
        //   $jwt_token = explode(' ', $jwt_token)[1];
        // if(!$exists = SecurityService::validateToken($jwt_token)) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => "Invalid token.",
        //         'data' => $jwt_token
        //     ], Response::HTTP_UNAUTHORIZED);
        // };

        return $next($request);
    }
}
