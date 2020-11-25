<?php

declare(strict_types=1);

namespace AppTest\City\Provider\Musement;

use App\City\DataTransfer\City;
use App\City\Exception\FailedToGetCitiesException;
use App\City\Provider\Musement\MusementCitiesProvider;
use AppTest\ExternalClientTestCase;
use Exception;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Prophecy\PhpUnit\ProphecyTrait;

final class MusementCitiesProviderTest extends ExternalClientTestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider getMusementCitiesClientExpectations
     */
    public function test_it_gets_cities_from_api(string $response, array $expected): void
    {
        $client = $this->getMusementClient($response);

        $cities = (new MusementCitiesProvider($client, $this->getSerializer()))->getAll();

        $this->assertEquals($expected, $cities);
    }

    private function getMusementClient(string $rawResponse): Client
    {
        $response = new Response(200, [], $rawResponse);

        $client = $this->prophesize(Client::class);
        $client->get('cities')->willReturn($response);

        return $client->reveal();
    }

    public function getMusementCitiesClientExpectations(): Generator
    {
        yield 'It gets cities' => [
            $this->getResponseContent(__DIR__ . '/get.cities.success.json'),
            [
                new City('Amsterdam', 52.374, 4.9,),
                new City('Paris', 48.866, 2.355)
            ]
        ];


        yield 'It it skips cities with unexpected data' => [
            $this->getResponseContent(__DIR__ . '/get.cities.without-name.json'),
            [
                new City('Paris', 48.866, 2.355)
            ]
        ];
    }

    public function test_it_will_throw_domain_exception_when_there_is_problem_with_api(): void
    {
        $client = $this->prophesize(Client::class);
        $client->get('cities')->willThrow(Exception::class);
        $client = $client->reveal();

        $this->expectException(FailedToGetCitiesException::class);

        (new MusementCitiesProvider($client, $this->getSerializer()))->getAll();
    }
}
