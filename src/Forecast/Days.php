<?php

declare(strict_types=1);

namespace App\Forecast;

final class Days
{
    private int $days;

    public function __construct(int $numberOfDays)
    {
        if ($numberOfDays < 1 || $numberOfDays > 10) {
            throw new \InvalidArgumentException(
                sprintf('Number of days must be between 1-10 but %d given', $numberOfDays)
            );
        }
    }

    public function get(): int
    {
        return $this->days;
    }
}
