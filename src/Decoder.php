<?php

declare(strict_types=1);

namespace App;

interface Decoder
{
    public function decode(string $data): array;
}
