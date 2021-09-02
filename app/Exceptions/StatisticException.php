<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class StatisticException extends Exception
{
    public static function notFound(string $message, Throwable $previous = null): self
    {
        return new self(sprintf('statistic not found for %s', $message), 0, $previous);
    }
}
