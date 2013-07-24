<?php

namespace ODTCreator;

// TODO: Refactor all places where these methods are used to use value objects instead and then get rid of this class
class Common
{
    public static function isColor($color)
    {
        //return preg_match('/(#[\dA-Fa-f]{6}|[A-Za-z]+|rgb\(\d{1,3},\d{1,3},\d{1,3}\))/', $color);
        return preg_match('/#[\dA-Fa-f]{6}/', $color);
    }

    public static function isNumeric($value, $nonNegative = false)
    {
        if ($nonNegative) {
            $pattern = '/\d+(.\d)*/';
        } else {
            $pattern = '/-?\d+(.\d)*/';
        }
        return preg_match($pattern, $value);
    }

    public static function isLengthValue($value, $nonNegative = false)
    {
        if ($nonNegative) {
            $pattern = '/\d*.?\d+(pt|cm|mm)/';
        } else {
            $pattern = '/-?\d*.?\d+(pt|cm|mm)/';
        }
        return preg_match($pattern, $value);
    }

    public static function isPercentage($value)
    {
        return preg_match('/\d+(.\d)*%/', $value);
    }
}
