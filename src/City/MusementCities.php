<?php

declare(strict_types=1);

namespace App\City;

use App\Decoder;
use GuzzleHttp\Client;
use Throwable;

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
            throw FailedToGetCities::because($exception->getMessage());
        }

        $responseData = $this->decoder->decode((string)$response->getBody());


        $cities = [];

        foreach ($responseData as $city) {
            if (!isset($city['name']) || !isset($city['latitude']) || !isset($city['longitude'])) {
                continue;
            }

            $cities[] = new City(
                $city['name'],
                new Coordinates($city['latitude'], $city['longitude'])
            );
        }

        return $cities;
    }
}
