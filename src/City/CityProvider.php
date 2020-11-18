<?php

declare(strict_types=1);

namespace App\City;

interface CityProvider
{
    /**
     * @return array<City>
     */
    public function getAll(): array;
}
