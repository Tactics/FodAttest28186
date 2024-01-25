<?php

namespace Tactics\FodAttest28186\Exception;

use Exception;
use Throwable;

final class NonMatchingBirthdayException extends Exception
{
    public function __construct($message = "", Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
