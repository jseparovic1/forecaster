<?php

declare(strict_types=1);

namespace App\Forecast;

use Psr\Container\ContainerInterface;

class WeatherApiForecastFactory
{
    public function __invoke(ContainerInterface $container): WeatherApiForecast
    {
        return new WeatherApiForecast();
    }
}
