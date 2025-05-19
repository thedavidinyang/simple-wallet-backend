<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class kedoolException extends Exception
{
    public function __construct(string $message = '', int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function context(): array
    {
        return request()->all();
    }
}
