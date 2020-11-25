<?php

declare(strict_types=1);

namespace App\Forecast\DTO;

class ForecastDTO
{
    /**
     * @var ForecastDayDTO[]
     */
    private array $forecasts;

    /**
     * @param ForecastDayDTO[] $forecasts
     */
    public function __construct(array $forecasts)
    {
        $this->forecasts = $forecasts;
    }

    /**
     * @return ForecastDayDTO[]
     */
    public function getDaily(): array
    {
        return $this->forecasts;
    }

    public function __toString(): string
    {
        $dailyForecasts = array_map(
            fn(ForecastDayDTO $day) => $day->getDay()->getCondition()->getText(),
            $this->getDaily()
        );

        return implode(' | ', $dailyForecasts);
    }
}
