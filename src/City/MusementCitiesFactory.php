<?php

declare(strict_types=1);

namespace App\City;

use App\JsonDecoder;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

final class MusementCitiesFactory
{
    public function __invoke(ContainerInterface $container): MusementCities
    {
        $config = $container->get('config')['musement-api'];

        // TODO validate config

        $client = new Client($config);

        return new MusementCities($client, new JsonDecoder());
    }
}
