<?php

declare(strict_types=1);

namespace App\City\Provider\Musement;

use App\City\DataTransfer\City;
use App\City\Exception\FailedToGetCities;
use App\City\Provider\CityProviderInterface;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

final class MusementCities implements CityProviderInterface
{
    private Client $client;
    private SerializerInterface $serializer;

    public function __construct(Client $client, SerializerInterface $serializer)
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

        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            sprintf('%s[]', City::class),
            'json',
            [
                AbstractNormalizer::GROUPS => 'api.city.get'
            ]
        );
    }
}
