<?php

declare(strict_types=1);

namespace App\Forecast\Provider;

final class RangeInDays
{
    private int $days;

    public function __construct(int $days)
    {
        if ($days < 1 || $days > 10) {
            throw new \InvalidArgumentException(
                sprintf('Number of days must be between 1-10 but %d given', $days)
            );
        }

        $this->days = $days;
    }

    public function get(): int
    {
        return $this->days;
    }
}
