<?php

declare(strict_types=1);

namespace App;

interface Decoder
{
    /**
     * @return array<mixed>
     */
    public function decode(string $data): array;
}
