<?php

declare(strict_types=1);

namespace App;

use function json_decode;

class JsonDecoder implements DecoderInterface
{
    private int $options = JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR;

    public function decode(string $data): array
    {
        return json_decode(
            $data, true,
            512,
            $this->options
        );
    }
}
