<?php

declare(strict_types=1);

namespace App\City;

use App\Decoder;
use GuzzleHttp\Client;
use Throwable;
use function array_map;

final class MusementCities implements CityProvider
{
    private Client $client;
    private Decoder $decoder;

    public function __construct(Client $client, Decoder $decoder)
    {
        $this->client = $client;
        $this->decoder = $decoder;
    }

    public function getAll(): array
    {
        try {
            $response = $this->client->get('cities');
        } catch (Throwable $exception) {
            // TODO catch and throw app exception.
            var_dump($exception->getMessage());
            die();
        }

        $responseData = $this->decoder->decode((string)$response->getBody());

        return array_map(
            function (array $city) {
                return new City(
                    $city['name'],
                    new Coordinates($city['latitude'], $city['longitude'])
                );
            },
            $responseData
        );
    }
}
