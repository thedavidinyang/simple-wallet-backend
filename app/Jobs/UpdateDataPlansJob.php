<?php

namespace App\Jobs;

use App\Models\DataPlans;
use App\Models\AirtimeNetwork;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateDataPlansJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $plans;

    /**
     * Create a new job instance.
     */
    public function __construct(array $plans)
    {
        $this->plans = $plans;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        foreach ($this->plans as $plan) {
            DataPlans::updateOrCreate(
                ['product_id' => $plan['productId']],
                [
                    'dataBundle' => $plan['dataBundle'],
                    'amount'     => $plan['amount'],
                    'validity'   => $plan['validity'],
                    'airtime_network_id' => AirtimeNetwork::where('network', 'LIKE', substr($plan['productId'], 0, 3) . '%')->first()->id,
                ]
            );
        }
    }
}
