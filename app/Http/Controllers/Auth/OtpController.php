<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\OtpMail;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class OtpController extends Controller
{
    public function verifyOtp(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:50',
            'otp' => 'required|max:6|exists:otps,code',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'message' => 'data validation failed',
                'errors' => $validator->errors(),
            ], 400);

        }

        $data = $validator->validated();

        // DB::beginTransaction();

        // DB::commit();



    }
}
