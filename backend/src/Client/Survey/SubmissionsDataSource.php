<?php

namespace IWD\JOBINTERVIEW\Client\Survey;

interface SubmissionsDataSource
{
    /**
     * Read all submitted surveys
     * @return iterable
     */
    public function data(): iterable;
}
