<?php

declare(strict_types=1);

namespace App\Command;

use App\City\Provider\CityProviderInterface;
use App\Forecast\Provider\ForecastProviderInterface;
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
