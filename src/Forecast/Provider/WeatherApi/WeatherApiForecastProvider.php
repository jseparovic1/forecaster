<?php

declare(strict_types=1);

namespace App\Forecast\Provider\WeatherApi;

use App\City\DataTransfer\City;
use App\Forecast\DataTransfer\Forecast;
use App\Forecast\Exception\FailedToGetForecast;
use App\Forecast\Provider\ForecastProvider;
use App\Forecast\Provider\RangeInDays;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Throwable;

class WeatherApiForecastProvider implements ForecastProvider
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
        } catch (GuzzleException $exception) {
            throw FailedToGetForecast::for($city, 'Accessing Weather API resulted in error.');
        }

        $body = (new JsonEncoder())->decode($response->getBody()->getContents(), JsonEncoder::FORMAT);

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
            throw FailedToGetForecast::for($city, 'Invalid forecast data received from API.');
        }

        assert($forecast instanceof Forecast);

        return $forecast;
    }

    /**
     * @param array<City> $cities
     * @return array<string, Forecast>
     */
    public function getForecasts(array $cities, RangeInDays $days): array
    {
        $promises = [];
        $forecasts = [];

        foreach ($cities as $city) {
            $promises[$city->getName()] = $this->client
                ->getAsync(
                    'forecast.json',
                    [
                        'query' => [
                            'q' => $city->getName(),
                            'days' => $days->get(),
                        ]
                    ]
                )
                ->then(
                    function (Response $response) use (&$forecasts, $city): void {
                        $body = (new JsonEncoder())->decode($response->getBody()->getContents(), JsonEncoder::FORMAT);

                        assert(is_array($body));

                        $forecast = $this->serializer->denormalize(
                            $body['forecast'] ?? null,
                            Forecast::class,
                            null,
                            [
                                AbstractNormalizer::GROUPS => 'api.forecast.get',
                            ]
                        );

                        if ($forecast instanceof Forecast) {
                            $forecasts[$city->getName()] = $forecast;
                        }
                    },
                    function (GuzzleException $exception) {
                        // Log...
                        error_log($exception->getMessage());
                    }
                );
        }

        Utils::settle($promises)->wait();

        return $forecasts;
    }
}
