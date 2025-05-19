<?php
namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{

    public function signIn($credentials)
    {

        try {


            return auth('api')->attempt($credentials);

        } catch (\Throwable $e) {


            return response()->json([
                'error'  => $e->getMessage(),
                'trace'  => $e->getTrace(),
                'status' => false,
            ], 500);
        }

    }
    public function signOut()
    {

        try {
            if (! auth('api')->check()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            JWTAuth::invalidate(JWTAuth::getToken());

            auth('api')->logout();

            return response()->json([
                'status'  => true,
                'message' => 'Logged out successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Logout failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
