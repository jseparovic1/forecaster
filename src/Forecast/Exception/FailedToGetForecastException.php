<?php

declare(strict_types=1);

namespace App\Forecast\Exception;

use App\City\DTO\CityDTO;
use RuntimeException;

class FailedToGetForecastException extends RuntimeException
{
    private CityDTO $city;

    public static function for(CityDTO $city, string $reason): self
    {
        $exception = new self(
            sprintf('Failed to get forecast for city %s. %s', $city, $reason)
        );

        $exception->city = $city;

        return $exception;
    }

    public static function fromApiData(CityDTO $city, string $reason): self
    {
        $exception = new self(
            sprintf('Failed to get forecast for city %s. %s', $city, $reason)
        );

        $exception->city = $city;

        return $exception;
    }

    public function getCity(): CityDTO
    {
        return $this->city;
    }
}
