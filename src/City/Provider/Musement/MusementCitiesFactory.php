<?php

declare(strict_types=1);

namespace App\City\Provider\Musement;

use App\City\Provider\CachedCityProvider;
use App\City\Provider\CityProviderInterface;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class MusementCitiesFactory
{
    public function __invoke(ContainerInterface $container): CityProviderInterface
    {
        $config = $container->get('config')['musement-api'];

        $client = new Client($config);

        return new CachedCityProvider(
            new MusementCities($client, $container->get(SerializerInterface::class))
        );
    }
}
