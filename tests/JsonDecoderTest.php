<?php

declare(strict_types=1);

namespace AppTest;

use App\JsonDecoder;
use Generator;
use JsonException;
use PHPUnit\Framework\TestCase;

final class JsonDecoderTest extends TestCase
{
    /**
     * @dataProvider getDecoderExpectations
     */
    public function test_it_decodes_json(string $data, array $expected): void
    {
        $decoder = new JsonDecoder();

        $this->assertEquals($expected, $decoder->decode($data));
    }

    public function getDecoderExpectations(): Generator
    {
        yield 'It decodes simple array' => [
            '["Split", "Milano"]',
            ['Split', 'Milano']
        ];

        yield 'It decodes object' => [
            '{"cities": ["Split", "Milano"]}',
            [
                'cities' => ['Split', 'Milano']
            ]
        ];
    }

    public function test_it_throws_exception_on_invalid_json(): void
    {
        $decoder = new JsonDecoder();

        $this->expectException(JsonException::class);

        $decoder->decode('{invalid-json-data]');
    }
}
