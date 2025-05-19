<?php
namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;
use Monolog\LogRecord;

class DiscordLogger
{
    public function __invoke(array $config): LoggerInterface
    {
        $logger = new Logger('discord');
        $logger->pushHandler(new DiscordHandler());

        return $logger;
    }
}

class DiscordHandler extends AbstractProcessingHandler
{
    protected $webhookUrl;

    public function __construct($level = null, $bubble = true)
    {
        $logLevel = $level ?? Logger::toMonologLevel(env('LOG_LEVEL', 'debug'));
        parent::__construct($logLevel, $bubble);

        $this->webhookUrl = env('DISC_WEBHOOK_PROD') ?? env('DISC_WEBHOOK_TEST') ?? '#discord chaneel url';
    }

    protected function write(LogRecord $record): void
    {
        if (!$this->webhookUrl) {
            return;
        }

        $message = "**[" . strtoupper($record->level->getName()) . "]** " . $record->message;
        if (!empty($record->context)) {
            $message .= "\n```json\n" . json_encode($record->context, JSON_PRETTY_PRINT) . "\n```";
        }

        try {
            Http::withoutVerifying()->post($this->webhookUrl, ['content' => $message]);
        } catch (\Exception $e) {
            // Handle the exception or log the error
            error_log('Failed to send log to Discord: ' . $e->getMessage());
        }
    }
}
