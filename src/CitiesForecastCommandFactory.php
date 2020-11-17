<?php

declare(strict_types=1);

namespace App;

use App\City\CityProvider;
use App\Forecast\ForecastProvider;
use Psr\Container\ContainerInterface;

class CitiesForecastCommandFactory
{
    public function __invoke(ContainerInterface $container): CitiesForecastCommand
    {
        return new CitiesForecastCommand(
            $container->get(CityProvider::class),
            $container->get(ForecastProvider::class)
        );
    }
}
