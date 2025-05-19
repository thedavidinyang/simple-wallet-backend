<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class VerifyPin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'Invalid PIN.'], 401);
        }

        return $next($request);
    }
}
