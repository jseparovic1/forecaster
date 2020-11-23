<?php

declare(strict_types=1);

namespace AppTest;

use App\Configuration;
use App\Serializer\ObjectNormalizerFactory;
use App\Serializer\SerializerFactory;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class ExternalClientTestCase extends TestCase
{
    use ProphecyTrait;

    protected function getSerializer(): Serializer
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ObjectNormalizer::class)->willReturn($this->getObjectNormalizer());
        $container = $container->reveal();

        return (new SerializerFactory())->__invoke($container);
    }

    protected function getObjectNormalizer(): ObjectNormalizer
    {
        $container = $this->prophesize(ContainerInterface::class);
        $config = (new ConfigAggregator(
            [
                Configuration::class,
                new PhpFileProvider(
                    realpath(__DIR__) . '/../config/autoload/{{,*.}global,{,*.}local}.php'
                ),
            ]
        ))->getMergedConfig();

        $container->get('config')->willReturn($config);
        $container = $container->reveal();

        return (new ObjectNormalizerFactory())->__invoke($container);
    }
}
