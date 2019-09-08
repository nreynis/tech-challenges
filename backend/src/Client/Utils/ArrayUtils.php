<?php

namespace IWD\JOBINTERVIEW\Client\Utils;

class ArrayUtils
{
    public static function flatmap(array $array, callable $callback)
    {
        return array_merge([], ...array_map($callback, $array));
    }
}
