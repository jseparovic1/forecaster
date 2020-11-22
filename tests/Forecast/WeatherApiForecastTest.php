<?php

declare(strict_types=1);

namespace AppTest\Forecast;

use App\City\City;
use App\City\Coordinates;
use App\Forecast\Days;
use App\Forecast\FailedToGetForecast;
use App\Forecast\Forecast;
use App\Forecast\WeatherApiForecastInterface;
use App\JsonDecoder;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class WeatherApiForecastTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider getWeatherApiForecastExceptions
     */
    public function test_it_gets_daily_forecast_for_city(
        City $city,
        Days $days,
        string $response,
        array $expected
    ): void {
        $client = $this->getWeatherApiClient($city, $days, $response);

        $forecasts = (new WeatherApiForecastInterface($client, new JsonDecoder()))
            ->getForecasts($city, $days);

        $this->assertEquals($expected, $forecasts);
    }

    private function getWeatherApiClient(City $city, Days $days, string $rawResponse): Client
    {
        $response = new Response(200, [], $rawResponse);

        $client = $this->prophesize(Client::class);
        $client->get(
            'forecast.json',
            [
                'query' => [
                    'q' => $city->name(),
                    'days' => $days->get()
                ]
            ]
        )->willReturn($response);

        return $client->reveal();
    }

    public function getWeatherApiForecastExceptions(): Generator
    {
        $createResponse = function (string $firstDayCondition, string $secondDayCondition): string {
            return json_encode(
                [
                    'forecast' => [
                        'forecastday' => [
                            [
                                'day' => [
                                    'condition' => [
                                        'text' => $firstDayCondition
                                    ]
                                ]
                            ],
                            [
                                'day' => [
                                    'condition' => [
                                        'text' => $secondDayCondition
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            );
        };

        yield 'It gets 2 day forecasts for Milano' => [
            new City('Milano', new Coordinates(43.51, 16.45)),
            new Days(2),
            $createResponse('Partly cloudy', 'Raining'),
            [
                new Forecast('Partly cloudy'),
                new Forecast('Raining')
            ]
        ];
    }

    public function test_it_will_throw_domain_exception_when_there_is_problem_with_api(): void
    {
        $client = $this->prophesize(Client::class);
        $client
            ->get('forecast.json', ["query" => ["q" => "::name::", "days" => 1]])
            ->willThrow(\Exception::class);
        $client = $client->reveal();

        $this->expectException(FailedToGetForecast::class);

        (new WeatherApiForecastInterface($client, new JsonDecoder()))
            ->getForecasts(
                new City('::name::', new Coordinates(9, 9)),
                new Days(1)
            );
    }

    public function test_it_will_throw_domain_exception_when_api_returns_invalid_data(): void
    {
        $client = $this->prophesize(Client::class);
        $client
            ->get('forecast.json', ["query" => ["q" => "::name::", "days" => 1]])
            ->willReturn(new Response(200, [], '["not-expected-response"]'));
        $client = $client->reveal();

        $this->expectException(FailedToGetForecast::class);

        (new WeatherApiForecastInterface($client, new JsonDecoder()))
            ->getForecasts(
                new City('::name::', new Coordinates(9, 9)),
                new Days(1)
            );
    }
}
