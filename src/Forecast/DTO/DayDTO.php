<?php

declare(strict_types=1);

namespace App\Forecast\DTO;

class DayDTO
{
    private ConditionDTO $condition;

    public function __construct(ConditionDTO $condition)
    {
        $this->condition = $condition;
    }

    public function getCondition(): ConditionDTO
    {
        return $this->condition;
    }
}
