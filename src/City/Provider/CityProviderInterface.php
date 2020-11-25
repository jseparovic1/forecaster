<?php

declare(strict_types=1);

namespace App\City\Provider;

use App\City\DTO\CityDTO;

interface CityProviderInterface
{
    /**
     * @return array<CityDTO>
     */
    public function getAll(): array;
}
