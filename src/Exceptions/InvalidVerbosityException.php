<?php

namespace Kusabi\Messages\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidVerbosityException extends InvalidArgumentException
{
    public function __construct(string $verbosity = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Invalid verbosity level '{$verbosity}'", $code, $previous);
    }
}
