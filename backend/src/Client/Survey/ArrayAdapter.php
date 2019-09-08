<?php

namespace IWD\JOBINTERVIEW\Client\Survey;

class ArrayAdapter implements SubmissionsDataSource
{
    /**
     * @var array
     */
    private $dataset;

    public function __construct(array $dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * @inheritDoc
     */
    public function data(): iterable
    {
        return $this->dataset;
    }
}
