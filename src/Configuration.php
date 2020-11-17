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
    public function __invoke(): array
    {
        return [
            'commands' => [
                CitiesForecastCommand::NAME => CitiesForecastCommand::class,
            ],
            'dependencies' => [
                'aliases' => [
                    CityProvider::class => MusementCities::class,
                    ForecastProvider::class => WeatherApiForecast::class,
                ],
                'factories' => [
                    CitiesForecastCommand::class => CitiesForecastCommandFactory::class,
                    MusementCities::class => MusementCitiesFactory::class,
                    WeatherApiForecast::class => WeatherApiForecastFactory::class
                ],
            ],
        ];
    }
}
