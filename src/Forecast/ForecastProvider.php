<?php

declare(strict_types=1);

namespace App\Forecast;

interface ForecastProvider
{
    /**
     * @param Days $days Number of days of weather forecast. Value ranges from 1 to 10
     */
    public function getForecasts(Days $days);
}
