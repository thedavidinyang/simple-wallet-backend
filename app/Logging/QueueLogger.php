<?php
namespace App\Logging;

use App\Jobs\LogToChannel;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class QueueLogger
{
    public function __invoke(array $config): LoggerInterface
    {
        return new QueueLoggerInstance($config);
    }
}

class QueueLoggerInstance implements LoggerInterface
{
    protected array $config;
    protected array $channels = ['daily', 'discord'];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function emergency(string | \Stringable $message, array $context = []): void
    {
        $this->log('emergency', $message, $context);
    }

    public function alert(string | \Stringable $message, array $context = []): void
    {
        $this->log('alert', $message, $context);
    }

    public function critical(string | \Stringable $message, array $context = []): void
    {
        $this->log('critical', $message, $context);
    }

    public function error(string | \Stringable $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    public function warning(string | \Stringable $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    public function notice(string | \Stringable $message, array $context = []): void
    {
        $this->log('notice', $message, $context);
    }

    public function info(string | \Stringable $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    public function debug(string | \Stringable $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }

    public function log($level, string | \Stringable $message, array $context = []): void
    {
        foreach ($this->channels as $channel) {
            dispatch(new LogToChannel($channel, $level, (string) $message, $context));
        }
    }
}
