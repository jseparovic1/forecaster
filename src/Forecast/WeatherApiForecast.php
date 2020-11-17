<?php

declare(strict_types=1);

namespace App\Forecast;

use App\City\City;
use App\Decoder;
use GuzzleHttp\Client;
use Throwable;

class WeatherApiForecast implements ForecastProvider
{
    private Client $client;
    private Decoder $decoder;

    public function __construct(Client $client, Decoder $decoder)
    {
        $this->client = $client;
        $this->decoder = $decoder;
    }

    /**
     * @return Forecast[]
     */
    public function getForecasts(City $city, Days $days): array
    {
        try {
            $response = $this->client->get(
                'forecast.json',
                [
                    'query' => [
                        'q' => $city->name(),
                        'days' => $days->get(),
                    ]
                ]
            );
        } catch (Throwable $exception) {
            // TODO catch and throw app exception.
            var_dump($exception->getMessage());
            die();
        }


        $responseData = $this->decoder->decode((string)$response->getBody());

        return array_map(
            function (array $forecast) {
                return new Forecast($forecast['day']['condition']['text']);
            },
            $responseData['forecast']['forecastday']
        );
    }
}
