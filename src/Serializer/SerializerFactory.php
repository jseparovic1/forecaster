<?php

declare(strict_types=1);

namespace App\Serializer;

use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerFactory
{
    public function __invoke(ContainerInterface $container): SerializerInterface
    {
        $objectNormalizer = $container->get(ObjectNormalizer::class);
        assert($objectNormalizer instanceof ObjectNormalizer);

        return new Serializer(
            [
                $objectNormalizer,
                new ArrayDenormalizer(),
                new DateTimeNormalizer(),
            ],
            [
                new JsonDecode(),
            ]
        );
    }
}
