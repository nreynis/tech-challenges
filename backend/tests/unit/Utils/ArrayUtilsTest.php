<?php

namespace IWD\JOBINTERVIEW\tests\unit\Utils;

use IWD\JOBINTERVIEW\Client\Utils\ArrayUtils;
use PHPUnit\Framework\TestCase;

class ArrayUtilsTest extends TestCase
{
    public function testFlatmap(): void
    {
        $words = ['lorem', 'ipsum', 'dolor'];
        $chars = ArrayUtils::flatmap($words, 'str_split');
        $this->assertEquals(str_split('loremipsumdolor'), $chars);
    }

    public function testEmptyFlatmap(): void
    {
        $words = [];
        $chars = ArrayUtils::flatmap($words, 'str_split');
        $this->assertEquals([], $chars);
    }
}
