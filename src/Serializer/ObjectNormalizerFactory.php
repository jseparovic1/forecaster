<?php

declare(strict_types=1);

namespace App\Serializer;

use Psr\Container\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\LoaderChain;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use Symfony\Component\Serializer\Mapping\Loader\XmlFileLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use function glob;

class ObjectNormalizerFactory
{
    public function __invoke(ContainerInterface $container): ObjectNormalizer
    {
        $config = $container->get('config')['normalization'];

        $metadataFactory = new ClassMetadataFactory(
            $this->getLoader($config['metadata_directories'])
        );

        return new ObjectNormalizer(
            $metadataFactory,
            new MetadataAwareNameConverter($metadataFactory),
            PropertyAccess::createPropertyAccessor(),
            new PropertyInfoExtractor(
                [
                    new ReflectionExtractor(),
                ],
                [
                    new PhpDocExtractor(),
                    new ReflectionExtractor(),
                ]
            ),
        );
    }

    /**
     * @param string[] $directories
     */
    private function getLoader(array $directories): LoaderInterface
    {
        /** @var LoaderInterface[] $loaders */
        $loaders = [];

        foreach ($directories as $directory) {
            $files = glob(realpath($directory) . '/*.xml');

            if ($files === false) {
                continue;
            }

            foreach ($files as $file) {
                $loaders[] = new XmlFileLoader($file);
            }
        }

        return new LoaderChain($loaders);
    }
}
