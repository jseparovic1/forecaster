<?php

declare(strict_types=1);

namespace App\City\DTO;

final class CoordinatesDTO
{
    private float $latitude;
    private float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }

    public function __toString(): string
    {
        return sprintf('%f,%f', $this->latitude, $this->longitude);
    }
}
