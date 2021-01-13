<?php

declare(strict_types=1);

namespace App\Forecast\Provider;

use App\City\DataTransfer\City;
use App\Forecast\DataTransfer\Forecast;

interface ForecastProvider
{
    /**
     * @param RangeInDays $days Number of days of weather forecast. Value ranges from 1 (today) to 10 (in 10 days)
     */
    public function getForecast(City $city, RangeInDays $days): Forecast;

    /**
     * @param array<City> $cities
     * @return array<Forecast>
     */
    public function getForecasts(array $cities, RangeInDays $days): array;
}
