<?php

declare(strict_types=1);

namespace App\Forecast;

class Day
{
    private Condition $condition;

    public function __construct(Condition $condition)
    {
        $this->condition = $condition;
    }

    public function getCondition(): Condition
    {
        return $this->condition;
    }
}
