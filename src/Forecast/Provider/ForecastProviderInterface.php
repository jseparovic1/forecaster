<?php

declare(strict_types=1);

namespace App\Forecast\Provider;

use App\City\City;
use App\Forecast\Days;
use App\Forecast\Forecast;

interface ForecastProviderInterface
{
    /**
     * @param Days $days Number of days of weather forecast. Value ranges from 1 to 10
     */
    public function getForecast(City $city, Days $days): Forecast;
}
