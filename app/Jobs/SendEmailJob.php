<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $mailableClass;
    public array $data;
    public string $recipient;

    /**
     * Create a new job instance.
     */
    public function __construct(string $mailableClass, string $recipient, array $data = [])
    {
        $this->mailableClass = $mailableClass;
        $this->recipient = $recipient;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Instantiate the mailable class dynamically
        $mailable = new $this->mailableClass(...$this->data);
        
        // Send the email
        Mail::to($this->recipient)->send($mailable);
    }
}
