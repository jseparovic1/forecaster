<?php

declare(strict_types=1);

namespace App\Forecast;

class Forecast
{
    /**
     * @var ForecastDay[]
     */
    private array $forecasts;

    public function __construct(array $forecasts)
    {
        $this->forecasts = $forecasts;
    }

    /**
     * @return ForecastDay[]
     */
    public function getDaily(): array
    {
        return $this->forecasts;
    }

    public function __toString(): string
    {
        $dailyForecasts = array_map(
            fn(ForecastDay $day) => $day->getDay()->getCondition()->getText(),
            $this->getDaily()
        );

        return implode('| ', $dailyForecasts);
    }
}
