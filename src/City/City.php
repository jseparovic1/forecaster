<?php

declare(strict_types=1);

namespace App\City;

class City
{
    private string $name;
    private Coordinates $coordinates;

    public function __construct(string $name, Coordinates $coordinates)
    {
        $this->name = $name;
        $this->coordinates = $coordinates;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function coordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function __toString(): string
    {
        return $this->name();
    }
}
