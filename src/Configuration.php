<?php

declare(strict_types=1);

namespace App;

use App\City\CityProviderInterface;
use App\City\MusementCitiesFactory;
use App\Forecast\ForecastProviderInterface;
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
                    CityProviderInterface::class => MusementCitiesFactory::class,
                    ForecastProviderInterface::class => WeatherApiForecastFactory::class
                ],
            ],
        ];
    }
}
