<?php

namespace IWD\JOBINTERVIEW\tests\unit\Survey;

use IWD\JOBINTERVIEW\Client\Survey\ArrayAdapter;
use IWD\JOBINTERVIEW\Client\Survey\SubmissionsDataSource;
use PHPUnit\Framework\TestCase;

class ArrayAdapterTest extends TestCase
{
    public function testConstructor(): void
    {
        $adapter = new ArrayAdapter([]);

        $this->assertInstanceOf(SubmissionsDataSource::class, $adapter);
        $this->assertInstanceOf(ArrayAdapter::class, $adapter);
    }

    public function testConstructorException(): void
    {
        $this->expectException(\ArgumentCountError::class);
        new ArrayAdapter();
    }

    public function testDataset(): void
    {
        $dataset = [1, 2, 3];
        $adapter = new ArrayAdapter($dataset);
        $this->assertEquals($dataset, $adapter->data());
    }

    public function testEmptyDataset(): void
    {
        $dataset = [];
        $adapter = new ArrayAdapter($dataset);
        $this->assertEquals($dataset, $adapter->data());
    }
}
