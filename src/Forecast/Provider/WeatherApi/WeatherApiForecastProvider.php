<?php

declare(strict_types=1);

namespace App\Forecast\Provider\WeatherApi;

use App\City\DataTransfer\City;
use App\Forecast\DataTransfer\Forecast;
use App\Forecast\Exception\FailedToGetForecastException;
use App\Forecast\Provider\ForecastProviderInterface;
use App\Forecast\Provider\RangeInDays;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Throwable;

class WeatherApiForecastProvider implements ForecastProviderInterface
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
            throw FailedToGetForecastException::for($city, 'Accessing Weather API resulted in error.');
        }

        if ($response->getStatusCode() !== 200) {
            throw FailedToGetForecastException::fromApiData($city, $response->getReasonPhrase());
        }

        $body = json_decode($response->getBody()->getContents(), true);

        try {
            $forecast = $this->serializer->denormalize(
                $body['forecast'] ?? null,
                Forecast::class,
                null,
                [
                    AbstractNormalizer::GROUPS => 'api.forecast.get',
                ]
            );
        } catch (Throwable $exception) {
            throw FailedToGetForecastException::for($city, 'Invalid forecast data received from API.');
        }

        assert($forecast instanceof Forecast);

        return $forecast;
    }
}
