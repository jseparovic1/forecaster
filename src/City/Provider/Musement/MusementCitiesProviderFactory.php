<?php

declare(strict_types=1);

namespace App\City\Provider\Musement;

use App\City\Provider\CachedCityProvider;
use App\City\Provider\CityProvider;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Serializer;

final class MusementCitiesProviderFactory
{
    public function __invoke(ContainerInterface $container): CityProvider
    {
        $config = $container->get('config')['musement-api'];

        $client = new Client($config);

        return new CachedCityProvider(
            new MusementCitiesProvider(
                $client,
                $container->get(Serializer::class)
            )
        );
    }
}
