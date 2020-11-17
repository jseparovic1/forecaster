<?php

declare(strict_types=1);

namespace AppTest;

use PHPUnit\Framework\TestCase;

final class TrueTest extends TestCase
{
    public function test_it_returns_true(): void
    {
        $mustBeTrue = true;

        $this->assertTrue($mustBeTrue);
    }
}
