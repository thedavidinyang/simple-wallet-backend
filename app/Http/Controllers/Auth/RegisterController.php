<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Mail\verifyEmail;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use App\Jobs\SendEmailJob;

class RegisterController extends Controller
{

    private function generateUUID()
    {

        return Str::uuid();
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name'   => 'required|string|max:25|regex:/^\S*$/',
            'last_name'    => 'required|string|max:25|regex:/^\S*$/',
            'middle_name'  => 'nullable|string|max:25|regex:/^\S*$/',
            'address'    => 'required|string|max:100',
            'gender'       => 'required|in:male,female',
            'email'        => 'required|string|email|max:50',
            'phone_number' => 'required|string|max:20|regex:/^\+?[0-9]{1,3}?[-.\s]?[0-9]{1,14}$/',
            // 'country_id'   => 'required|exists:countries,id',
            'password'     => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'referral_id'  => ['bail', 'nullable', 'string', 'exists:users,username'],
            'date_of_birth' => 'required|date|before:today',
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
            $existing_phone = User::where('phone', $request->phone_number)->exists();

            if ($existing_phone) {

                return response()->json([
                    'status'  => false,
                    'message' => 'Phone number already registerd',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            //check for referral_id  in request

            $referral_id_exists = array_key_exists("referral_id", $validatedData);
            $referrer           = null;
            if ($referral_id_exists) {
                $referrer = User::where('username', $validatedData['referral_id'])->first();
            }

            if ($referral_id_exists && isset($referrer)) {
                $validatedData['referral_id'] = $referrer->id;
            }

            $userData = [
                'email'    => $request->email,
                'password' => $request->password,
                'phone'    => $request->phone_number,
                'gender'   => $request->gender,
            ];

            $user = User::create($userData);

            $userProfileData = [
                'first_name'  => $request->first_name,
                'last_name'   => $request->last_name,
                'middle_name' => $request->middle_name ?? null,
                'email'       => $request->email,
                'phone'       => $request->phone_number,
                'address'       => $request->address,
                'date_of_birth'       => $request->date_of_birth,

            ];

            $user->profile()->create($userProfileData);

            $otp = $this->generateOtp($user);

            DB::commit();
           
            Mail::to($user->email)->send(new OtpMail($otp['code'], $userProfileData));



            requestLog($request, 'registration succesfull', 'info', 'user.register', 'success', $user);

            return response()->json([
                'status'  => true,
                'message' => 'Registration successful,Verify Email',
                'data'    => [
                    'user'    => $user,
                    'profile' => $user->profile,
                ],
            ], 200);

        } catch (\Throwable $e) {

            requestLog($request, $e, 'error', 'user.register', 'failed');

            return response()->json([
                'status'  => false,
                'message' => 'an error occurred',
                'error'   => $e->getMessage(),
            ], 200);

        }

    }

    public function resendOtp(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status'  => false,
                    'message' => 'data validation failed',
                    'errors'  => $validator->errors(),
                ], 400);

            }

            $user = User::where('email', $request->email)->first();

            if ($user->email_verified) {

                return response()->json([
                    'status'  => false,
                    'message' => 'email already verified',
                    'error'   => 'email verification failed',
                ], 404);
            }

            $otp = $this->generateOtp($user);

            $userProfileData = [
                'first_name'  => $user->profile->first_name,
                'last_name'   => $user->profile->last_name,
                'middle_name' => $user->profile->middle_name ?? null,
                'email'       => $user->email,
                'phone'       => $user->phone_number,

            ];

            Mail::to($user->email)->send(new OtpMail($otp['code'], $userProfileData));


            requestLog($request, 'otp send succesfully succesfull', 'info', 'user.resend.email.otp', 'success', $user);

            return response()->json([
                'status'  => true,
                'message' => 'New OTP sent to email',
                'data'    => [],
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'an error occurred',
                'error'   => $e->getMessage(),
                'trace'   => $e->getTrace(),
            ], 200);

        }

    }

    public function verifyOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'code'  => 'required|integer|digits:6',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status'  => false,
                'message' => 'data validation failed',
                'errors'  => $validator->errors(),
            ], 400);

        }

        try {

            $user = User::where('email', $request->email)->first();

            $otp = Otp::where('user_id', $user->id)
                ->where('code', $request->code)
                ->where('expired_at', '>', Carbon::now())
                ->whereNull('used_at')
                ->first();

            if ($otp) {

                $user->update(['email_verified_at' => Carbon::now()]);

                $otp->update(['used_at' => Carbon::now()]);

                $userProfileData = [
                    'first_name'  => $user->profile->first_name,
                    'last_name'   => $user->profile->last_name,
                    'middle_name' => $user->profile->middle_name ?? null,
                    'email'       => $user->email,
                    'phone'       => $user->phone_number,

                ];

                Mail::to($user->email)->send(new verifyEmail($otp['code'], $userProfileData));




                requestLog($request, 'user email succesfully succesfull', 'info', 'user.email.verified', 'success', $user);

                return response()->json([
                    'status'  => true,
                    'message' => 'Email verified successfully.',
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Invalid or expired OTP.']
                , 400);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'an error occurred',
                'error'   => $e->getMessage(),
            ], 200);

        }

    }

    public function generateOtp(User $user)
    {

        try {

            return $user->otps()->create([
                'code'       => random_int(100000, 999999),
                'used_for'   => 'email_verification',
                'expired_at' => Carbon::now()->addMinutes((int) env('TOKEN_EXPIRY_TIME')),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'an error occurred',
                'error'   => $e->getMessage(),
            ], 500);

        }

    }

}
