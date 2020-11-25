<?php

declare(strict_types=1);

namespace App\City\DTO;

class CityDTO
{
    private string $name;
    private CoordinatesDTO $coordinates;

    public function __construct(string $name, float $latitude, float $longitude)
    {
        $this->name = $name;
        $this->coordinates = new CoordinatesDTO($latitude, $longitude);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCoordinates(): CoordinatesDTO
    {
        return $this->coordinates;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
