<?php

declare(strict_types=1);

namespace App\Forecast;

use App\City\City;
use App\DecoderInterface;
use GuzzleHttp\Client;
use Throwable;

class WeatherApiForecastInterface implements ForecastProviderInterface
{
    private Client $client;
    private DecoderInterface $decoder;

    public function __construct(Client $client, DecoderInterface $decoder)
    {
        $this->client = $client;
        $this->decoder = $decoder;
    }

    /**
     * @return array<Forecast>
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
            throw FailedToGetForecast::for($city, 'Accessing Weather API resulted in error.');
        }

        $responseData = $this->decoder->decode((string)$response->getBody());

        if (!isset($responseData['forecast']['forecastday'])) {
            throw FailedToGetForecast::fromApiData($city, 'Data for daily forecasts is unknown.');
        }

        $dailyForecasts = $responseData['forecast']['forecastday'];
        $forecasts = [];

        foreach ($dailyForecasts as $i => $dailyForecast) {
            if (!isset($dailyForecast['day']['condition']['text'])) {
                throw FailedToGetForecast::for(
                    $city,
                    sprintf('Missing forecastday.%d.day.condition.text', $i)
                );
            }

            $forecasts[] = new Forecast($dailyForecast['day']['condition']['text']);
        }

        return $forecasts;
    }
}
