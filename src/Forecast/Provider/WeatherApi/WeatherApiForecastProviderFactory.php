<?php

declare(strict_types=1);

namespace App\Forecast\Provider\WeatherApi;

use App\Forecast\Provider\ForecastProvider;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\Serializer\Serializer;

class WeatherApiForecastProviderFactory
{
    public function __invoke(ContainerInterface $container): ForecastProvider
    {
        $config = $container->get('config')['weather_api'];

        $addApiKey = function (string $apiKey) {
            return function (callable $handler) use ($apiKey) {
                return function (RequestInterface $request, array $options) use ($handler, $apiKey) {
                    $query = $request->getUri()->getQuery();

                    $uri = $request->getUri()->withQuery(sprintf('key=%s&%s', $apiKey, $query));

                    return $handler($request->withUri($uri), $options);
                };
            };
        };

        $stack = HandlerStack::create();
        $stack->push($addApiKey($config['key']));

        $client = new Client(
            [
                'base_uri' => $config['base_uri'],
                'handler' => $stack
            ]
        );

        return new WeatherApiForecastProvider($client, $container->get(Serializer::class));
    }
}
