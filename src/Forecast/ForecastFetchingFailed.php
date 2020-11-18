<?php

declare(strict_types=1);

namespace App\Forecast;

use App\City\City;
use RuntimeException;

class ForecastFetchingFailed extends RuntimeException
{
    private City $city;

    public static function for(City $city): self
    {
        $exception = new self(
            sprintf('Failed to get forecast for city %s', $city)
        );

        $exception->city = $city;
    }

    public function getCity(): City
    {
        return $this->city;
    }
}
