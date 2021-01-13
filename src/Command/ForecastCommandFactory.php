<?php

declare(strict_types=1);

namespace App\Command;

use App\City\Provider\CityProvider;
use App\Forecast\Provider\ForecastProvider;
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
