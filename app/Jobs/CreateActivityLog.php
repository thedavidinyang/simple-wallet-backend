<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\ActivityLog;
use App\Models\User;


use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;


class CreateActivityLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

     private $user;
     private $action;
    public function __construct( User $user, string $action )
    {
        
        $this->user = $user;
        $this->action = $action;
    }

    /**
     * Execute the job.
     */
    public function handle(Request $request): void
    {
        //

        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));
    
        // Get more detailed device information
        $deviceType = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');

        $activityData = [
            'action' => $this->action,
            'device' => "{$deviceType}",  // Detailed device info
            'ip_address' => $request->ip(),
            'user_id' => $this->user->id,
        ];
    
        ActivityLog::create( $activityData);

        Log::info('activity log:'.$this->action,  $activityData);

    }
}
