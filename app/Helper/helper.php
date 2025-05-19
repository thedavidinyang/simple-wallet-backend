<?php

use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Str;



function pushNotify(array $data)
{

    return (new \App\Http\Controllers\Firebase\PushNotificationController())->send($data);
}

function userFmcT()
{

    return auth()->user()->fcm_token;
}

function sendPushNotification(string $message): void
{
    pushNotify([
        'token' => userFmcT(),
        'topic' => 'Silicash',
        'body'  => $message,
    ]);
}

function user()
{
    return auth()->user();
}

function requestLog(Request $request, $message, $level, $action, $status = 'success', $user = null)
{

    $agent = (new Jenssegers\Agent\Agent);

    $agent->setUserAgent($request->header('User-Agent'));

    $deviceType = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');

    \Illuminate\Support\Facades\Log::channel('stack')->$level("operation $status", [
        'action'     => $action,
        'device'     => "{$deviceType}",
        'ip_address' => $request->ip(),
        'request'    => $request->all(),
        'message'    => $message,
        'user'       => $user ? $user->uuid : null,
    ]);
}

function ActivityLog(Request $request, $action)
{

    $user = user();

    $agent = (new Jenssegers\Agent\Agent);

    $agent->setUserAgent($request->header('User-Agent'));

    // Get more detailed device information
    $deviceType = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');

    \Illuminate\Support\Facades\Log::channel('stack')->info('operation succesfull', [
        'user_id'    => $user->uuid,
        'action'     => $action,
        'device'     => "{$deviceType}", // Detailed device info
        'ip_address' => $request->ip(),
    ]);

    \App\Models\ActivityLog::create([
        'action'     => $action,
                                         // 'location' => $this->user->country?->name,
        'device'     => "{$deviceType}", // Detailed device info
        'ip_address' => $request->ip(),
        'user_id'    => $user->id,

    ]);

}

function apiMode()
{

    return env('API_MODE', 'test');
}

function activity($user, $action)
{
    \App\Jobs\CreateActivityLog::dispatch($user, $action);
}

function userActions($action)
{
   activity(auth()->user(), $action);
}

function getRoleId($role)
{

    $staffRole = (new \App\Models\StaffRole())->where('name', $role)->first();

    return $staffRole->id;

}

function permision($permission){

    if (!Gate::allows('can-do', $permission)) {
        throw new AuthorizationException('Unauthorized');
    }
}

function systemConfig($key){

    return \App\Models\Configuration::where('key', $key)->select('key', 'value')->first();
}

function getTransferFee($ammount){

    return (new \App\Models\TransferFee())->where('min_amount', '<=', $ammount)->where('max_amount', '>=', $ammount)->value('fee');
}

function generateTransactionRef($lenght = 19)
    {
        return 'SILI' . Str::upper(Str::random($lenght));
    }


    function maskNumber(int $number): string
    {
        $numberStr = str_pad((string)$number, 10, '0', STR_PAD_LEFT); 
    
        $firstPart = substr($numberStr, 0, 4);
        $lastPart = substr($numberStr, -2);
    
        return $firstPart . '****' . $lastPart;
    }