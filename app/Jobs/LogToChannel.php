<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogToChannel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected string $channel;
    protected string $level;
    protected string $message;
    protected array $context;

    public function __construct(string $channel, string $level, string $message, array $context = [])
    {
        $this->channel = $channel;
        $this->level = $level;
        $this->message = $message;
        $this->context = json_decode(json_encode($context), true);
    }

    public function handle()
    {
        try {
            Log::channel($this->channel)->{$this->level}($this->message, $this->context);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), [$e]);
        }
    }
}