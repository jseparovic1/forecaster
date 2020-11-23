<?php

declare(strict_types=1);

namespace App\Forecast\Provider\WeatherApi;

use App\Forecast\Provider\ForecastProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\Serializer\Serializer;

class WeatherApiForecastFactory
{
    public function __invoke(ContainerInterface $container): ForecastProviderInterface
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

        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push($addApiKey($config['key']));

        $client = new Client(
            [
                'base_uri' => $config['base_uri'],
                'handler' => $stack
            ]
        );

        return new WeatherApiForecast($client, $container->get(Serializer::class));
    }
}
