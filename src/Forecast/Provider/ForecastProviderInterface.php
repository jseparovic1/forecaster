<?php

declare(strict_types=1);

namespace App\Forecast\Provider;

use App\City\DTO\CityDTO;
use App\Forecast\DTO\ForecastDTO;

interface ForecastProviderInterface
{
    /**
     * @param RangeInDays $days Number of days of weather forecast. Value ranges from 1 (today) to 10 (in 10 days)
     */
    public function getForecast(CityDTO $city, RangeInDays $days): ForecastDTO;
}
