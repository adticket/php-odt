<?php

namespace Juit\PhpOdt\OdtCreator\Value;

use Juit\PhpOdt\OdtCreator\Style\StyleException;

class Color
{
    /**
     * @var string
     */
    private $hexCode;

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return Color
     */
    public static function fromRgb($red, $green, $blue)
    {
        $hexCode = '#' . self::dechex($red) . self::dechex($green) . self::dechex($blue);

        return new self($hexCode);
    }

    /**
     * @param int $input
     * @return string
     */
    private static function dechex($input)
    {
        return str_pad(dechex($input), 2, '0', STR_PAD_LEFT);
    }

    public function __construct($hexCode)
    {
        if (!preg_match('/^#[\dA-Fa-f]{6}$/', $hexCode)) {
            throw new StyleException("Invalid hex color code '$hexCode'");
        }
        $this->hexCode = $hexCode;
    }

    /**
     * @return string
     */
    public function getHexCode()
    {
        return $this->hexCode;
    }
}
