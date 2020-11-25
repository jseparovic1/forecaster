<?php

declare(strict_types=1);

namespace App\Forecast\DTO;

use DateTimeInterface;

class ForecastDayDTO
{
    private DayDTO $day;
    private DateTimeInterface $date;

    public function __construct(DateTimeInterface $date, DayDTO $day)
    {
        $this->day = $day;
        $this->date = $date;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getDay(): DayDTO
    {
        return $this->day;
    }
}
