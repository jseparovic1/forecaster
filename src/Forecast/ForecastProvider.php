<?php

declare(strict_types=1);

namespace App\Forecast;

use App\City\City;

interface ForecastProvider
{
    /**
     * @param Days $days Number of days of weather forecast. Value ranges from 1 to 10
     */
    public function getForecasts(City $city, Days $days);
}
