<?php

declare(strict_types=1);

namespace AppTest\City\Provider\Musement;

use App\City\DataTransfer\City;
use App\City\Exception\FailedToGetCities;
use App\City\Provider\Musement\MusementCities;
use AppTest\ExternalClientTestCase;
use Exception;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Prophecy\PhpUnit\ProphecyTrait;

final class MusementCitiesTest extends ExternalClientTestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider getMusementCitiesClientExpectations
     */
    public function test_it_gets_cities_from_api(string $response, array $expected): void
    {
        $client = $this->getMusementClient($response);

        $cities = (new MusementCities($client, $this->getSerializer()))->getAll();

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
            json_encode(
                [
                    [
                        'name' => 'Split',
                        'latitude' => 43.51,
                        'longitude' => 16.45,
                    ],
                    [
                        'name' => 'Milano',
                        'latitude' => 45.5,
                        'longitude' => 9.1,
                    ]
                ],
            ),
            [
                new City('Split', 43.51, 16.45),
                new City('Milano', 45.5, 9.1)
            ]
        ];

        yield 'It it skips cities without coordinates' => [
            json_encode(
                [
                    [
                        'name' => 'Skip me',
                    ],
                    [
                        'name' => 'Milano',
                        'latitude' => 45.5,
                        'longitude' => 9.1,
                    ]
                ],
            ),
            [
                new City('Milano', 45.5, 9.1)
            ]
        ];

        yield 'It it skips cities without name' => [
            json_encode(
                [
                    [
                        // Missing city name.
                        'latitude' => 43.51,
                        'longitude' => 16.45,
                    ],
                    [
                        'name' => 'Milano',
                        'latitude' => 45.5,
                        'longitude' => 9.1,
                    ]
                ],
            ),
            [
                new City('Milano', 45.5, 9.1)
            ]
        ];
    }

    public function test_it_will_throw_domain_exception_when_there_is_problem_with_api(): void
    {
        $client = $this->prophesize(Client::class);
        $client->get('cities')->willThrow(Exception::class);
        $client = $client->reveal();

        $this->expectException(FailedToGetCities::class);

        (new MusementCities($client, $this->getSerializer()))->getAll();
    }
}
