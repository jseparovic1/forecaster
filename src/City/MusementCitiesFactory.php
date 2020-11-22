<?php

declare(strict_types=1);

namespace App\City;

use App\JsonDecoder;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

final class MusementCitiesFactory
{
    public function __invoke(ContainerInterface $container): CityProviderInterface
    {
        $config = $container->get('config')['musement-api'];

        $client = new Client($config);

        return new CachedCityProvider(
            new MusementCities($client, new JsonDecoder())
        );
    }
}
