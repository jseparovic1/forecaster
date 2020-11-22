<?php

declare(strict_types=1);

namespace App\Forecast;

use DateTimeInterface;

class ForecastDay
{
    private Day $day;
    private DateTimeInterface $date;

    public function __construct(DateTimeInterface $date, Day $day)
    {
        $this->day = $day;
        $this->date = $date;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getDay(): Day
    {
        return $this->day;
    }
}
