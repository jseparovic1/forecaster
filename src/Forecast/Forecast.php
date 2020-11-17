<?php

declare(strict_types=1);

namespace App\Forecast;

class Forecast
{
    private string $condition;

    public function __construct(string $condition)
    {
        $this->condition = $condition;
    }

    public function __toString(): string
    {
        return $this->condition;
    }
}
