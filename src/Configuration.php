<?php

declare(strict_types=1);

namespace App;

use App\City\Provider\CityProvider;
use App\City\Provider\Musement\MusementCitiesProviderFactory;
use App\Command\ForecastCommand;
use App\Command\ForecastCommandFactory;
use App\Forecast\Provider\ForecastProvider;
use App\Forecast\Provider\WeatherApi\WeatherApiForecastProviderFactory;
use App\Serializer\ObjectNormalizerFactory;
use App\Serializer\SerializerFactory;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
                    Serializer::class => SerializerFactory::class,
                    ForecastCommand::class => ForecastCommandFactory::class,
                    CityProvider::class => MusementCitiesProviderFactory::class,
                    ForecastProvider::class => WeatherApiForecastProviderFactory::class,
                    ObjectNormalizer::class => ObjectNormalizerFactory::class
                ],
            ],
        ];
    }
}
