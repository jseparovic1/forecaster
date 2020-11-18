<?php

declare(strict_types=1);

namespace App;

use App\City\CityProvider;
use App\City\MusementCities;
use App\City\MusementCitiesFactory;
use App\Forecast\ForecastProvider;
use App\Forecast\WeatherApiForecast;
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
                'aliases' => [
                    CityProvider::class => MusementCities::class,
                    ForecastProvider::class => WeatherApiForecast::class,
                ],
                'factories' => [
                    ForecastCommand::class => ForecastCommandFactory::class,
                    MusementCities::class => MusementCitiesFactory::class,
                    WeatherApiForecast::class => WeatherApiForecastFactory::class
                ],
            ],
        ];
    }
}
