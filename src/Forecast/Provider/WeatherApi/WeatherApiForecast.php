<?php

declare(strict_types=1);

namespace App\Forecast\Provider\WeatherApi;

use App\City\City;
use App\Forecast\FailedToGetForecast;
use App\Forecast\Forecast;
use App\Forecast\Provider\ForecastProviderInterface;
use App\Forecast\Provider\RangeInDays;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Throwable;

class WeatherApiForecast implements ForecastProviderInterface
{
    private Client $client;
    private Serializer $serializer;

    public function __construct(Client $client, Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function getForecast(City $city, RangeInDays $days): Forecast
    {
        try {
            $response = $this->client->get(
                'forecast.json',
                [
                    'query' => [
                        'q' => $city->getName(),
                        'days' => $days->get(),
                    ]
                ]
            );
        } catch (Throwable $exception) {
            throw FailedToGetForecast::for($city, 'Accessing Weather API resulted in error.');
        }

        $body = json_decode($response->getBody()->getContents(), true);

        $forecast = $this->serializer->denormalize(
            $body['forecast'] ?? null,
            Forecast::class,
            null,
            [
                AbstractNormalizer::GROUPS => 'api.forecast.get',
            ]
        );

        assert($forecast instanceof Forecast);

        return $forecast;
    }
}
