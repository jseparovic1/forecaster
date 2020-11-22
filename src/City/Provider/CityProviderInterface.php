<?php

declare(strict_types=1);

namespace App\City\Provider;

use App\City\City;

interface CityProviderInterface
{
    /**
     * @return array<City>
     */
    public function getAll(): array;
}
