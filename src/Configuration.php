<?php

declare(strict_types=1);

namespace App;

use App\City\CityProvider;
use App\City\MusementCitiesFactory;
use App\Forecast\ForecastProvider;
use App\Forecast\WeatherApiForecastFactory;

class Configuration
{
    /**
     * @return array<string, array>
     */
    public function __invoke(): array
    {
        return [
            'commands' => [
                ForecastCommand::NAME => ForecastCommand::class,
            ],
            'dependencies' => [
                'factories' => [
                    ForecastCommand::class => ForecastCommandFactory::class,
                    CityProvider::class => MusementCitiesFactory::class,
                    ForecastProvider::class => WeatherApiForecastFactory::class
                ],
            ],
        ];
    }
}
