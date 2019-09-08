<?php

namespace IWD\JOBINTERVIEW\tests\unit\Survey;

use IWD\JOBINTERVIEW\Client\Exceptions\DirectoryNotFoundException;
use IWD\JOBINTERVIEW\Client\Survey\JsonFileAdapter;
use IWD\JOBINTERVIEW\Client\Survey\SubmissionsDataSource;
use PHPUnit\Framework\TestCase;

class JsonFileAdapterTest extends TestCase
{
    public function testConstructor(): void
    {
        $adapter = new JsonFileAdapter(__DIR__);

        $this->assertInstanceOf(SubmissionsDataSource::class, $adapter);
        $this->assertInstanceOf(JsonFileAdapter::class, $adapter);
    }

    public function testConstructorException(): void
    {
        $this->expectException(\ArgumentCountError::class);
        new JsonFileAdapter();
    }

    public function testConstructorPathException(): void
    {
        $this->expectException(DirectoryNotFoundException::class);
        new JsonFileAdapter('/no/such/directory');
    }

    public function testSimpleJsonDataset(): void
    {
        $adapter = new JsonFileAdapter(__DIR__.'/../../fixtures/simple-jsons/');
        $jsons = $adapter->data();
        $count = 0;
        foreach ($jsons as $json) {
            $this->assertTrue(in_array($json, [
                ["file" => "a.json"],
                ["file" => "b.json"],
                ["file" => "c.json"],
                ["file" => "d.json"],
                ["file" => "e.json"]
            ]));
            $count++;
        }
        // have we read all files ?
        $this->assertEquals(5, $count);
    }

    public function testSkipNonJsonDataset(): void
    {
        $adapter = new JsonFileAdapter(__DIR__.'/../../fixtures/mixed-jsons-txt/');
        $jsons = $adapter->data();
        $count = 0;
        foreach ($jsons as $json) {
            $this->assertNotEquals(["file" => "c.json"], $json);
            $count++;
        }
        // have we read all files ?
        $this->assertEquals(4, $count);
    }

    public function testSkipInvalidJsonDataset(): void
    {
        $adapter = new JsonFileAdapter(__DIR__.'/../../fixtures/invalid-jsons-file-c/');
        $jsons = $adapter->data();
        $count = 0;
        foreach ($jsons as $json) {
            $this->assertNotEquals(["file" => "c.json"], $json);
            $count++;
        }
        // have we read all files ?
        $this->assertEquals(4, $count);
    }

    public function testEmptyDataset(): void
    {
        $adapter = new JsonFileAdapter(__DIR__.'/../../fixtures/empty/');
        $jsons = $adapter->data();
        $count = 0;
        foreach ($jsons as $json) {
            $count++;
        }
        $this->assertEquals(0, $count);

        $jsons = $adapter->data();
        $this->assertEquals([], iterator_to_array($jsons));
    }
}
