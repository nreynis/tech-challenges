<?php

namespace IWD\JOBINTERVIEW\tests\functionnal;

use Silex\WebTestCase;

class SurveyControllerTest extends WebTestCase
{
    public function createApplication()
    {
        define('ROOT_PATH', realpath('.'));
        $app = require __DIR__.'/../../src/Client/Webapp/app.php';
        $app['data_path'] = 'tests/fixtures/simple-surveys';
        return $app;
    }

    public function testSurveyList()
    {
        $client = $this->createClient();
        $client->request('GET', '/survey/');
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $responseText = $response->getContent();
        $this->assertJson($responseText);

        $responseJson = json_decode($responseText, true);
        $expected = [
            ['name' => 'Survey A', 'code' => 'SA'],
            ['name' => 'Survey B', 'code' => 'SB']
        ];
        $this->assertTrue(in_array($expected[0], $responseJson));
        $this->assertTrue(in_array($expected[1], $responseJson));
    }

    public function testSurveyListRejectNonGet()
    {
        $client = $this->createClient();
        $client->request('POST', '/survey/');
        $response = $client->getResponse();
        $this->assertEquals(405, $response->getStatusCode());

        $client->request('PUT', '/survey/');
        $response = $client->getResponse();
        $this->assertEquals(405, $response->getStatusCode());

        $client->request('DELETE', '/survey/');
        $response = $client->getResponse();
        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testAggregate()
    {
        $client = $this->createClient();
        $client->request('GET', '/survey/SA');
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $responseText = $response->getContent();
        $this->assertJson($responseText);

        $responseJson = json_decode($responseText, true);
        $this->assertCount(3, $responseJson);
    }

    public function testAggregateRejectNonGet()
    {
        $client = $this->createClient();
        $client->request('POST', '/survey/SA');
        $response = $client->getResponse();
        $this->assertEquals(405, $response->getStatusCode());

        $client->request('PUT', '/survey/SA');
        $response = $client->getResponse();
        $this->assertEquals(405, $response->getStatusCode());

        $client->request('DELETE', '/survey/SA');
        $response = $client->getResponse();
        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testAggregateNotFoundForUnknownSurvey()
    {
        $client = $this->createClient();
        $client->request('GET', '/survey/SC');
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
}
