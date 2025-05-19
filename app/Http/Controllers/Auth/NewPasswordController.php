<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required',
            'password' => 'required|min:10|confirmed'
        ]);

        $status = Password::reset($request->all(), function (User $user, $password) {
            $user->update(['password' => $password]);
        });

        throw_unless($status == Password::PASSWORD_RESET,
            ValidationException::withMessages(['password' => __($status)])
        );

        return Response::api('Your Password has been reset successfully');
    }
}
