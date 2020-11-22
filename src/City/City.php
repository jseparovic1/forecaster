<?php

declare(strict_types=1);

namespace App\City;

class City
{
    private string $name;
    private Coordinates $coordinates;

    public function __construct(string $name, float $latitude, float $longitude)
    {
        $this->name = $name;
        $this->coordinates = new Coordinates($latitude, $longitude);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
