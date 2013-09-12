<?php

namespace Juit\PhpOdt\OdtCreator\Value;

use Juit\PhpOdt\OdtCreator\Style\StyleException;

class Color
{
    /**
     * @var string
     */
    private $hexCode;

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
