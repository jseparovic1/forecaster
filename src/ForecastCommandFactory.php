<?php

declare(strict_types=1);

namespace App;

use App\City\CityProvider;
use App\Forecast\ForecastProvider;
use Psr\Container\ContainerInterface;

class ForecastCommandFactory
{
    public function __invoke(ContainerInterface $container): ForecastCommand
    {
        return new ForecastCommand(
            $container->get(CityProvider::class),
            $container->get(ForecastProvider::class)
        );
    }
}
