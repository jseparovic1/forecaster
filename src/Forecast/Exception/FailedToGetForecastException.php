<?php

declare(strict_types=1);

namespace App\Forecast\Exception;

use App\City\DataTransfer\City;
use RuntimeException;

class FailedToGetForecastException extends RuntimeException
{
    private City $city;

    public static function for(City $city, string $reason): self
    {
        $exception = new self(
            sprintf('Failed to get forecast for city %s. %s', $city, $reason)
        );

        $exception->city = $city;

        return $exception;
    }

    public static function fromApiData(City $city, string $reason): self
    {
        $exception = new self(
            sprintf('Failed to get forecast for city %s. %s', $city, $reason)
        );

        $exception->city = $city;

        return $exception;
    }

    public function getCity(): City
    {
        return $this->city;
    }
}
