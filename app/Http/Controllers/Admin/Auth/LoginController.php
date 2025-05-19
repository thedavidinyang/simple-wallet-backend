<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Models\LoginLog;
use App\Models\RefreshToken;
use App\Models\User;
use App\Service\AuthService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {

            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $email = $credentials['email'];

            $token = (new AuthService)->signIn($credentials);

            if (! $token) {

                $this->trackLoginAttempt($email);

                if ($this->shouldSendOtp($email)) {

                    //block account here

                    return response()->json([
                        'status'  => false,
                        'message' => 'To many login attempts, Your account has been disabled, contact support',
                        'error'   => 'account disabled',
                    ], 400);
                }

                $no_of_attempts_left = $this->loginAttemptsLeft($email);

                return response()->json([
                    'status'  => false,
                    'message' => "login failed, you have $no_of_attempts_left attempts left",
                    'error'   => 'sign in failed',
                ], 400);
            }

            $user = auth()->user();

            if ($user->role != 'staff') {

                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthorized',
                    'error'   => 'unauthorized',
                ], 401);

            }

            $refresh_token = self::recordLoginLog($user, $request->header('User-Agent'), $request->ip(), $token);

            activity($user, 'admin.user.login');

            $user_details = $user->load('staff.roles.permissions');

            $user_details['refresh_token'] = $refresh_token;
            // $user_details['refresh_token_details'] = RefreshToken::where('token', $refresh_token)->first() ?? null;

            return response()->json([
                'status'  => true,
                'message' => 'Login successful',
                'data'    => [

                    'user'       => $user_details,
                    'token'      => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() * 60,
                ],
            ], 200);

        } catch (\Throwable $e) {

            requestLog($request, $e, 'error', 'user.login', 'failed');

            return response()->json([
                'error'  => $e->getMessage(),
                'trace'  => $e->getTrace(),
                'status' => false,
            ], 500);
        }
    }

    public static function generateRefreshToken($userId)
    {
        $token = Str::random(60);

        $expiresAt = Carbon::now()->addMonths(3);

        $refresh_token = RefreshToken::where('user_id', $userId)->first();

        if (! empty($refresh_token)) {

            $refresh_token_details = $refresh_token->update([
                'expires_at' => $expiresAt,
            ]);

        } else {

            $refresh_token_details = RefreshToken::Create(
                ['user_id'   => $userId,
                    'token'      => $token,
                    'expires_at' => $expiresAt,
                ]
            );

            $refresh_token = RefreshToken::where('token', $token)->first();

        }

        return $refresh_token->token;
    }

    public static function recordLoginLog($user, $device, $ip_address, $token)
    {
        $refresh_token = self::generateRefreshToken($user->id);

        $agent = new Agent();
        $agent->setUserAgent($device);

        LoginLog::create([
            'user_id'     => $user->id,
            'device'      => $device,
            'ip_address'  => $ip_address,
            'jwt_token'   => $token,
            'browser'     => $agent->browser(),
            'platform'    => $agent->platform(),
            'device_name' => $agent->device(),
        ]);

        //activate user
        if (! $user->active) {
            $user->update([
                'active' => true,
            ]);
        }

        return $refresh_token;

    }

    public static function validateRefreshToken($token)
    {
        $refreshToken = RefreshToken::where('token', $token)->first();

        if ($refreshToken && $refreshToken->expires_at->isFuture()) {
            return $refreshToken->user_id;
        }

        return null;
    }

    protected function trackLoginAttempt($email)
    {

        LoginAttempt::create([
            'email'        => $email,
            'attempted_at' => now(),
        ]);
    }

    protected function shouldSendOtp($email)
    {
        $attempts = LoginAttempt::where('email', $email)
            ->where('attempted_at', '>=', now()->subMinutes(7))
            ->count();

        return $attempts >= 7;
    }

    protected function loginAttemptsLeft($email)
    {
        $attempts = LoginAttempt::where('email', $email)
            ->where('attempted_at', '>=', now()->subMinutes(7))
            ->count();

        return 7 - $attempts;
    }

    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $token  = $request->input('refresh_token');
        $userId = self::validateRefreshToken($token);

        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $token                 = JWTAuth::fromUser($user);
                $refresh_token         = self::recordLoginLog($user, $request->header('User-Agent'), $request->ip(), $token);
                $refresh_token_details = RefreshToken::where('token', $refresh_token)->first() ?? null;

                return response()->json([
                    'status'  => true,
                    'message' => 'New Token Generated',
                    'data'    => [
                        'refresh_token'         => $refresh_token,
                        'refresh_token_details' => $refresh_token_details,
                        'token'                 => $token,
                    ],
                ]);
            }
        }

        return response()->json([
            'status'  => false,
            'message' => 'Invalid or expired refresh token',
        ], 401);
    }

}
