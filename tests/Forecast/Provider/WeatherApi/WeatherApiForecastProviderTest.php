<?php

declare(strict_types=1);

namespace AppTest\Forecast\Provider\WeatherApi;

use App\City\DTO\CityDTO;
use App\Forecast\DTO\ForecastDayDTO;
use App\Forecast\Exception\FailedToGetForecastException;
use App\Forecast\Provider\RangeInDays;
use App\Forecast\Provider\WeatherApi\WeatherApiForecastProvider;
use AppTest\ExternalClientTestCase;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Prophecy\PhpUnit\ProphecyTrait;

final class WeatherApiForecastProviderTest extends ExternalClientTestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider getWeatherApiForecastExceptions
     */
    public function test_it_gets_daily_forecast_for_city(
        CityDTO $city,
        RangeInDays $days,
        string $response,
        array $expected
    ): void {
        $client = $this->getWeatherApiClient($city, $days, $response);

        $forecast = (new WeatherApiForecastProvider($client, $this->getSerializer()))
            ->getForecast($city, $days);


        $this->assertEquals(
            $expected,
            array_map(
                fn(ForecastDayDTO $day) => $day->getDay()->getCondition()->getText(),
                $forecast->getDaily()
            )
        );
    }

    private function getWeatherApiClient(CityDTO $city, RangeInDays $days, string $rawResponse): Client
    {
        $response = new Response(200, [], $rawResponse);

        $client = $this->prophesize(Client::class);
        $client->get(
            'forecast.json',
            [
                'query' => [
                    'q' => $city->getName(),
                    'days' => $days->get()
                ]
            ]
        )->willReturn($response);

        return $client->reveal();
    }

    public function getWeatherApiForecastExceptions(): Generator
    {
        yield 'It gets 2 day forecasts for Milano' => [
            new CityDTO('Milano', 43.51, 16.45),
            new RangeInDays(2),
            $this->getResponseContent(__DIR__ . '/get.milan-two-day-forecast.success.json'),
            [
                'Partly cloudy',
                'Partly cloudy'
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

        $this->expectException(FailedToGetForecastException::class);

        (new WeatherApiForecastProvider($client, $this->getSerializer()))
            ->getForecast(
                new CityDTO('::name::', 9, 9),
                new RangeInDays(1)
            );
    }

    public function test_it_will_throw_domain_exception_when_api_returns_invalid_data(): void
    {
        $client = $this->prophesize(Client::class);
        $client
            ->get('forecast.json', ["query" => ["q" => "::name::", "days" => 1]])
            ->willReturn(new Response(200, [], '["not-expected-response"]'));
        $client = $client->reveal();

        $this->expectException(FailedToGetForecastException::class);

        (new WeatherApiForecastProvider($client, $this->getSerializer()))
            ->getForecast(
                new CityDTO('::name::', 9, 9),
                new RangeInDays(1)
            );
    }
}
