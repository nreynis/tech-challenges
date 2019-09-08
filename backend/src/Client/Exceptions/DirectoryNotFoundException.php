<?php

namespace IWD\JOBINTERVIEW\Client\Exceptions;

class DirectoryNotFoundException extends \InvalidArgumentException implements AppException
{
    public function __construct($path = '')
    {
        parent::__construct("Cannot find a directory at '$path'");
    }
}
