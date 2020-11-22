<?php

declare(strict_types=1);

namespace App\City\Provider;

use App\City\City;

class CachedCityProvider implements CityProviderInterface
{
    /** @var array<City>|null */
    private ?array $cache = null;

    private CityProviderInterface $decorated;

    public function __construct(CityProviderInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @return array<City>
     */
    public function getAll(): array
    {
        if ($this->cache === null) {
            $this->cache = $this->decorated->getAll();
        }

        return $this->cache;
    }
}
