<?php

declare(strict_types=1);

namespace App;

use App\City\CityProviderInterface;
use App\Forecast\ForecastProviderInterface;
use Psr\Container\ContainerInterface;

class ForecastCommandFactory
{
    public function __invoke(ContainerInterface $container): ForecastCommand
    {
        return new ForecastCommand(
            $container->get(CityProviderInterface::class),
            $container->get(ForecastProviderInterface::class)
        );
    }
}
