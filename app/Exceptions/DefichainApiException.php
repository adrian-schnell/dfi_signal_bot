<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DefichainApiException extends Exception
{
    public static function generic(string $message, Throwable $previous): self
    {
        return new self($message, 0, $previous);
    }
}
