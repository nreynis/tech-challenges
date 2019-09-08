<?php

namespace IWD\JOBINTERVIEW\Client\Utils;

class MathUtils
{
    public static function median(array $values): float
    {
        sort($values);
        $count = count($values);
        if($count === 0){
            return NAN;
        }
        else if($count%2 === 1){
            return $values[floor($count/2)];
        }
        else{
            return ($values[$count/2-1] + $values[$count/2]) / 2;
        }
    }
}
