<?php

namespace IWD\JOBINTERVIEW\Client\Exceptions;

class UnknownResponseType extends \UnexpectedValueException implements AppException
{
    public function __construct($type = '')
    {
        parent::__construct("Unknown response of type '$type'");
    }
}
