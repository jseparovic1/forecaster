<?php

declare(strict_types=1);

namespace App\City\Exception;

use RuntimeException;

class FailedToGetCitiesException extends RuntimeException
{
    public static function because(string $reason): self
    {
        return new self($reason);
    }
}
