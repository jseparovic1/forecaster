<?php

declare(strict_types=1);

namespace App\City\Provider\Musement;

use App\City\DataTransfer\City;
use App\City\Exception\FailedToGetCities;
use App\City\Provider\CityProviderInterface;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Throwable;

final class MusementCities implements CityProviderInterface
{
    private Client $client;
    private Serializer $serializer;

    public function __construct(Client $client, Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @return array<City>
     */
    public function getAll(): array
    {
        try {
            $response = $this->client->get('cities');
        } catch (Throwable $exception) {
            throw FailedToGetCities::because('Getting data from Musement API resulted in exception.');
        }

        if ($response->getStatusCode() !== 200) {
            throw FailedToGetCities::because($response->getReasonPhrase());
        }

        $body = json_decode($response->getBody()->getContents(), true);

        $cities = [];

        foreach ($body as $cityData) {
            try {
                $city = $this->serializer->denormalize(
                    $cityData,
                    City::class,
                    null,
                    [
                        AbstractNormalizer::GROUPS => 'api.city.get'
                    ]
                );

                assert($city instanceof City);
                $cities[] = $city;
            } catch (Throwable $exception) {
                // Skip invalid records...
            }
        }

        return $cities;
    }
}
