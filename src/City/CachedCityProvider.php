<?php

declare(strict_types=1);

namespace App\City;

class CachedCityProvider implements CityProvider
{
    /** @var array<City>|null */
    private ?array $cache = null;

    private CityProvider $decorated;

    public function __construct(CityProvider $decorated)
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
