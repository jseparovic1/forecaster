<?php

declare(strict_types=1);

namespace App\Forecast\DTO;

class ConditionDTO
{
    private string $text;
    private ?string $icon;

    public function __construct(string $text, ?string $icon = null)
    {
        $this->text = $text;
        $this->icon = $icon;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }
}
