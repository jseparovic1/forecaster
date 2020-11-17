<?php

declare(strict_types=1);

namespace App\City;

interface CityProvider
{
    /**
     * @return City[]
     */
    public function getAll(): array;
}
