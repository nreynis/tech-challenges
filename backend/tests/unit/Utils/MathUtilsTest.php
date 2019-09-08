<?php

namespace IWD\JOBINTERVIEW\tests\unit\Utils;

use IWD\JOBINTERVIEW\Client\Utils\MathUtils;
use PHPUnit\Framework\TestCase;

class MathUtilsTest extends TestCase
{
    public function testMedianOdd(): void
    {
        $median = MathUtils::median([1, 2, 3, 4, 5]);
        $this->assertEquals(3, $median);
    }

    public function testMedianEven(): void
    {
        $median = MathUtils::median([1, 2, 3, 4, 5, 6]);
        $this->assertEquals(3.5, $median);
    }

    public function testMedianUnsortedOdd(): void
    {
        $median = MathUtils::median([3, 1, 5, 4, 2]);
        $this->assertEquals(3, $median);
    }

    public function testMedianUnsortedEven(): void
    {
        $median = MathUtils::median([2, 1, 5, 4, 6, 3]);
        $this->assertEquals(3.5, $median);
    }

    public function testMedianEquals(): void
    {
        $median = MathUtils::median([48, 48, 48, 48, 48, 48, 48, 48, 48, 48, 48, 48]);
        $this->assertEquals(48, $median);
    }

    public function testMedianEmptySet(): void
    {
        $median = MathUtils::median([]);
        $this->assertNan($median);
    }
}
