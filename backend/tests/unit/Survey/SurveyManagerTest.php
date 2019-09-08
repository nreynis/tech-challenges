<?php

namespace IWD\JOBINTERVIEW\tests\unit\Survey;

use IWD\JOBINTERVIEW\Client\Survey\SubmissionsDataSource;
use IWD\JOBINTERVIEW\Client\Survey\SurveyManager;
use PHPUnit\Framework\TestCase;

class SurveyManagerTest extends TestCase
{
    /**
     * Fixture for mocked data source
     * @var array
     */
    private $testResults;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->testResults = require 'tests/fixtures/surveyResults.php';
        parent::__construct($name, $data, $dataName);
    }

    private function getStubDataSource(iterable $dataset): SubmissionsDataSource
    {
        $stubDataSource = $this->createMock(SubmissionsDataSource::class);
        $stubDataSource
            ->method('data')
            ->willReturn($dataset);
        return $stubDataSource;
    }

    public function testArrayGetAllSubmissions(): void
    {
        $manager = new SurveyManager($this->getStubDataSource($this->testResults));
        $this->assertEquals($this->testResults, $manager->getAllSubmissions());
    }

    public function testIteratorGetAllSubmissions(): void
    {
        $manager = new SurveyManager($this->getStubDataSource(new \ArrayIterator($this->testResults)));
        $this->assertEquals($this->testResults, $manager->getAllSubmissions());
    }

    public function testGetAllSurveys(): void
    {
        $manager = new SurveyManager($this->getStubDataSource($this->testResults));
        $surveys = $manager->getAllSurveys();
        $count = 0;
        $foundSA = false;
        $foundSB = false;
        foreach ($surveys as $survey){
            if($survey['code'] === 'SA'){
                $foundSA = true;
                $count++;
            }
            else if($survey['code'] === 'SB'){
                $foundSB = true;
                $count++;
            }
        }
        // have we found all surveys only once ?
        $this->assertEquals(2, $count);
        $this->assertTrue($foundSA);
        $this->assertTrue($foundSB);
    }

    public function testGetSubmissions(): void
    {
        $manager = new SurveyManager($this->getStubDataSource($this->testResults));
        $this->assertCount(2, $manager->getSubmissions(['SA']));
        $this->assertCount(1, $manager->getSubmissions(['SB']));
        $this->assertCount(3, $manager->getSubmissions(['SA', 'SB']));
    }

    public function testResultsAggregation(): void
    {
        $manager = new SurveyManager($this->getStubDataSource($this->testResults));
        $aggregation = $manager->aggregate($manager->getAllSubmissions());
        $expected = [
            [
                'type' => 'qcm',
                'label' => 'Question 1?',
                'numberOfResponses' => 3,
                'volumePerOption' => [
                    'Option 1' => 2,
                    'Option 2' => 1,
                    'Option 3' => 1,
                    'Option 4' => 0
                ]
            ],
            [
                'type' => 'numeric',
                'label' => 'Question 2?',
                'numberOfResponses' => 3,
                'minimum' => 12,
                'average' => (float) 16,
                'maximum' => 21,
                'median' => (float) 15
            ],
            [
                'type' => 'date',
                'label' => 'Question 3?',
                'dates' => [
                    '2019-09-07T15:21:45.000Z',
                    '2019-09-07T16:21:45.000Z',
                    '2019-09-07T18:21:45.000Z',
                ],
                'groupedByWeek' => [
                    '2019 week 36' => 3
                ]
            ]
        ];
        $this->assertEquals($expected, $aggregation);
    }
}
