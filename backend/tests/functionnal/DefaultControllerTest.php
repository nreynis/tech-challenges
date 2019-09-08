<?php

namespace IWD\JOBINTERVIEW\tests\functionnal;

use Silex\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function createApplication()
    {
        define('ROOT_PATH', realpath('.'));
        $app = require __DIR__ . '/../../src/Client/Webapp/app.php';
        $app['data_path'] = 'tests/fixtures/simple-jsons';
        return $app;
    }

    public function testRoot()
    {
        $client = $this->createClient();
        $client->request('GET', '/');
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $responseText = $response->getContent();
        $this->assertJson($responseText);
    }
}
