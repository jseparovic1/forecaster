<?php

declare(strict_types=1);

namespace App\City;

interface CityProviderInterface
{
    /**
     * @return array<City>
     */
    public function getAll(): array;
}
