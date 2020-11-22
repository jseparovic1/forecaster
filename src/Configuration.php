<?php

declare(strict_types=1);

namespace App;

use App\City\Provider\CityProviderInterface;
use App\City\Provider\Musement\MusementCitiesFactory;
use App\Command\ForecastCommand;
use App\Command\ForecastCommandFactory;
use App\Forecast\Provider\ForecastProviderInterface;
use App\Forecast\Provider\WeatherApi\WeatherApiForecastFactory;
use App\Serializer\ObjectNormalizerFactory;
use App\Serializer\SerializerFactory;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

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
                    SerializerInterface::class => SerializerFactory::class,
                    ForecastCommand::class => ForecastCommandFactory::class,
                    CityProviderInterface::class => MusementCitiesFactory::class,
                    ForecastProviderInterface::class => WeatherApiForecastFactory::class,
                    ObjectNormalizer::class => ObjectNormalizerFactory::class
                ],
            ],
        ];
    }
}
