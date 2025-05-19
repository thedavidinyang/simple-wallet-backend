<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:25|regex:/^\S*$/',
            'email'     => 'required|string|email|max:50',
            'password'  => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status'  => false,
                'message' => 'data validation failed',
                'errors'  => $validator->errors(),
            ], 400);

        }

        $validatedData = $validator->validated();

        try {

            DB::beginTransaction();

            $existing_email = User::where('email', $request->email)->exists();

            if ($existing_email) {

                return response()->json([
                    'status'  => false,
                    'message' => 'Email already registerd',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $userData = [
                'email'     => $request->email,
                'password'  => $request->password,
                'full_name' => $request->full_name,
            ];

            $user = User::create($userData);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Registration successful',
                'data'    => [
                    'user' => $user,
                ],
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'an error occurred',
                'error'   => $e->getMessage(),
            ], 200);

        }

    }

}
